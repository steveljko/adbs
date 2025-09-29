<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\ImportProgressUpdated;
use App\Actions\Tag\AttachOrCreateTagsAction;
use App\Actions\Website\GetFaviconAction;
use App\Models\Bookmark;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ImportBookmarksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $bookmarks,
        private int $userId,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AttachOrCreateTagsAction $attachOrCreateTags,
        GetFaviconAction $getFavicon
    ): void {
        $totalBookmarks = count($this->bookmarks);
        $processed = 0;
        $failed = 0;
        $successful = 0;

        $this->broadcastProgress($processed, $totalBookmarks, $successful, $failed, 'Starting import...');

        DB::transaction(function () use ($attachOrCreateTags, $getFavicon, $totalBookmarks, &$processed, &$failed, &$successful) {
            foreach ($this->bookmarks as $bookmark) {
                try {
                    // TODO: Implement proper validation

                    Bookmark::query()
                        ->whereUserId($this->userId)
                        ->whereNotNull('imported_at')
                        ->where('can_undo', true)
                        ->update(['can_undo' => false]);

                    $toInsert = [
                        'url' => $bookmark['url'],
                        'title' => $bookmark['title'],
                        'favicon' => $getFavicon->execute($bookmark['url'], 32),
                        'status' => $bookmark['status'] ?? 'active',
                        'user_id' => $this->userId,
                        'can_undo' => true,
                        'imported_at' => now(),
                    ];

                    $b = Bookmark::create($toInsert);

                    if (isset($bookmark['tags']) && is_array($bookmark['tags'])) {
                        $attachOrCreateTags->execute(bookmark: $b, tags: $bookmark['tags']);
                    }

                    $successful++;
                    $processed++;

                    if ($processed % 5 === 0 || $processed === $totalBookmarks) {
                        $this->broadcastProgress(
                            $processed,
                            $totalBookmarks,
                            $successful,
                            $failed,
                            "Processing: {$bookmark['title']}"
                        );
                    }
                } catch (Exception $e) {
                    $failed++;
                    $processed++;

                    Log::warning('Failed to import bookmark using ImportBookmarksJob', [
                        'bookmark' => $bookmark,
                        'error' => $e->getMessage(),
                        'user_id' => $this->userId,
                    ]);

                    if ($processed % 5 === 0 || $processed === $totalBookmarks) {
                        $this->broadcastProgress(
                            $processed,
                            $totalBookmarks,
                            $successful,
                            $failed,
                            "Error processing: {$bookmark['title']}"
                        );
                    }
                }
            }
        });

        $this->broadcastProgress(
            $processed,
            $totalBookmarks,
            $successful,
            $failed,
            'Import completed!',
            true
        );
    }

    private function broadcastProgress(
        int $processed,
        int $total,
        int $successful,
        int $failed,
        string $message,
        bool $completed = false
    ): void {
        $percentage = $total > 0 ? round(($processed / $total) * 100, 2) : 0;

        $progressData = [
            'processed' => $processed,
            'total' => $total,
            'successful' => $successful,
            'failed' => $failed,
            'percentage' => $percentage,
            'message' => $message,
            'completed' => $completed,
            'updated_at' => now()->toISOString(),
        ];

        broadcast(new ImportProgressUpdated($this->userId, $progressData));
    }
}
