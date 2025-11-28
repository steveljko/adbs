<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Bookmark\CreateBookmarkAction;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ImportBookmarksJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $bookmarks,
        private int $userId,
    ) {}

    public function handle(CreateBookmarkAction $action): void
    {
        // Check if batch has been cancelled
        if ($this->batch()?->cancelled()) {
            return;
        }

        $processed = 0;
        $failed = 0;

        foreach ($this->bookmarks as $bookmark) {
            if ($this->batch()?->cancelled()) {
                break;
            }

            try {
                $action->execute([
                    'title' => $bookmark['title'],
                    'url' => $bookmark['url'],
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

        Log::info('Chunk processed', [
            'user_id' => $this->userId,
            'processed' => $processed,
            'failed' => $failed,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Import job failed', [
            'user_id' => $this->userId,
            'batch_id' => $this->batch()?->id,
            'error' => $exception?->getMessage(),
        ]);
    }

    protected function updateBatchProgress(): void
    {
        if (! $this->batch()) {
            return;
        }

        $batch = $this->batch();
        $progress = $batch->progress();

        $cachedProgress = Cache::get("upload_progress_{$this->userId}");
        $totalBookmarks = $cachedProgress['total'] ?? 0;

        Cache::put("upload_progress_{$this->userId}", [
            'progress' => round($progress, 2),
            'status' => 'processing',
            'message' => "Processing bookmarks... {$progress}% complete",
            'total' => $totalBookmarks,
            'total_jobs' => $batch->totalJobs,
            'pending_jobs' => $batch->pendingJobs,
            'failed_jobs' => $batch->failedJobs,
            'timestamp' => now()->toIso8601String(),
        ], 3600);
    }
}
