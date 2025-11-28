<?php

declare(strict_types=1);

namespace App\Actions\Bookmark;

use App\Exceptions\Bookmark\Import\EmptyBookmarkFileException;
use App\Jobs\ImportBookmarksJob;
use Illuminate\Bus\Batch;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ImportBookmarksAction
{
    public function execute($file, ?string $password = null)
    {
        $content = is_string($file) ? file_get_contents($file) : file_get_contents($file->getRealPath());
        $bookmarks = json_decode($content, true);

        if (isset($bookmarks['encrypted']) && $bookmarks['encrypted'] && $password) {
            $bookmarks = $this->decryptBookmarks($bookmarks, $password);
        } else {
            $bookmarks = $bookmarks['data'] ?? $bookmarks;
        }

        if (! is_array($bookmarks) || empty($bookmarks)) {
            throw new EmptyBookmarkFileException;
        }

        $this->dispatchImportJob($bookmarks);
    }

    /*
     * Checks if json file data is encrypted or not.
     */
    public function isEncrypted($file): bool
    {
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        return isset($data['encrypted']) && $data['encrypted'] === true;
    }

    /**
     * Runs bookmarks insert job
     */
    private function dispatchImportJob(array $bookmarks): void
    {
        $userId = Auth::id();
        $chunkSize = 100;

        Cache::put("upload_progress_{$userId}", [
            'progress' => 0,
            'status' => 'processing',
            'message' => 'Starting upload...',
            'total' => count($bookmarks),
            'processed' => 0,
        ], 3600);

        $chunks = array_chunk($bookmarks, $chunkSize);
        $jobs = [];

        foreach ($chunks as $chunk) {
            $jobs[] = new ImportBookmarksJob($chunk, $userId);
        }

        Bus::batch($jobs)
            ->name("Import Bookmarks - User {$userId}")
            ->then(function (Batch $batch) use ($userId) {
                Cache::put("upload_progress_{$userId}", [
                    'progress' => 100,
                    'status' => 'completed',
                    'message' => 'Import completed successfully',
                    'timestamp' => now()->toIso8601String(),
                ], 3600);
            })
            ->catch(function (Batch $batch, Throwable $e) use ($userId) {
                Cache::put("upload_progress_{$userId}", [
                    'progress' => $batch->progress(),
                    'status' => 'failed',
                    'message' => 'Import failed: '.$e->getMessage(),
                    'timestamp' => now()->toIso8601String(),
                ], 3600);
            })
            ->finally(function (Batch $batch) use ($userId) {
                Log::info("Batch import finished for user {$userId}", [
                    'batch_id' => $batch->id,
                    'total_jobs' => $batch->totalJobs,
                    'failed_jobs' => $batch->failedJobs,
                ]);
            })
            ->dispatch();
    }

    /**
     * Decrypts data part from JSON file
     */
    private function decryptBookmarks(array $encryptedData, string $password): array
    {
        $key = hash('sha256', $password, true);
        $decrypter = new Encrypter($key, 'AES-256-CBC');

        $decryptedJson = $decrypter->decrypt($encryptedData['data']);

        return json_decode($decryptedJson, true);
    }
}
