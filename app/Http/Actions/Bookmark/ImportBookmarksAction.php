<?php

declare(strict_types=1);

namespace App\Http\Actions\Bookmark;

use App\Exceptions\Bookmark\Import\EmptyBookmarkFileException;
use App\Http\Actions\Tag\AttachOrCreateTagsAction;
use App\Http\Actions\Website\GetFaviconAction;
use App\Jobs\ImportBookmarksJob;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;

final class ImportBookmarksAction
{
    public function __construct(
        private AttachOrCreateTagsAction $attachOrCreateTags,
        private GetFaviconAction $getFavicon
    ) {}

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

        return $this->dispatchImportJob($bookmarks);
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

        if (count($bookmarks) > $chunkSize) {
            $chunks = array_chunk($bookmarks, $chunkSize);
            foreach ($chunks as $chunk) {
                ImportBookmarksJob::dispatch($chunk, $userId);
            }
        } else {
            ImportBookmarksJob::dispatch($bookmarks, $userId);
        }
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
