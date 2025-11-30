<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Requests\Bookmark\CreateBookmarkRequest;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

final class ImportBookmarksJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $bookmarks,
        private int $userId,
    ) {}

    public function handle(CreateBookmarkAction $action): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $processed = 0;
        $failed = 0;

        $validatedBookmarks = $this->validateBookmarks();

        foreach ($validatedBookmarks as $bookmark) {
            if ($this->batch()?->cancelled()) {
                break;
            }

            try {
                $action->execute([
                    'url' => $bookmark['url'],
                    'title' => $bookmark['title'],
                    'tags' => $bookmark['tags'] ?? [],
                ], $this->userId);

                $processed++;
                $this->updateBatchProgress();
            } catch (Exception $e) {
                $failed++;
                Log::error('Bookmark import error', [
                    'user_id' => $this->userId,
                    'url' => $bookmark['url'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $failedValidation = count($this->bookmarks) - count($validatedBookmarks);

        Log::info('Chunk processed', [
            'user_id' => $this->userId,
            'processed' => $processed,
            'failed_validation' => $failedValidation,
            'failed_execution' => $failed,
            'total_in_chunk' => count($this->bookmarks),
        ]);
    }

    protected function validateBookmarks(): array
    {
        $validatedBookmarks = [];

        foreach ($this->bookmarks as $index => $bookmark) {
            if (! is_array($bookmark)) {
                Log::warning('Bookmark is not an array', [
                    'user_id' => $this->userId,
                    'index' => $index,
                    'type' => gettype($bookmark),
                ]);

                continue;
            }

            $validator = Validator::make($bookmark, (new CreateBookmarkRequest()->rules()));

            if ($validator->fails()) {
                Log::warning('Invalid bookmark data', [
                    'user_id' => $this->userId,
                    'index' => $index,
                    'url' => $bookmark['url'] ?? 'missing',
                    'title' => $bookmark['title'] ?? 'missing',
                    'errors' => $validator->errors()->toArray(),
                ]);

                continue;
            }

            if (isset($bookmark['tags']) && is_array($bookmark['tags'])) {
                $uniqueTags = array_unique($bookmark['tags']);
                if (count($uniqueTags) !== count($bookmark['tags'])) {
                    Log::warning('Duplicate tags found', [
                        'user_id' => $this->userId,
                        'url' => $bookmark['url'],
                    ]);
                    $bookmark['tags'] = array_values($uniqueTags);
                }
            }

            $validatedBookmarks[] = $validator->validated();
        }

        Log::info('Validation complete', [
            'user_id' => $this->userId,
            'total' => count($this->bookmarks),
            'valid' => count($validatedBookmarks),
            'invalid' => count($this->bookmarks) - count($validatedBookmarks),
        ]);

        return $validatedBookmarks;
    }

    protected function updateBatchProgress(): void
    {
        if (! $this->batch()) {
            return;
        }

        $batch = $this->batch();
        $progress = $batch->progress();

        Cache::put("upload_progress_{$this->userId}", [
            'progress' => round($progress, 2),
            'status' => 'processing',
            'message' => "Processing bookmarks... {$progress}% complete",
            'timestamp' => now()->toIso8601String(),
        ], 3600);
    }
}
