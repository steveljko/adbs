<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Actions\Tag\AttachOrCreateTagsAction;
use App\Http\Actions\Website\GetFaviconAction;
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
        private int $userId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AttachOrCreateTagsAction $attachOrCreateTags,
        GetFaviconAction $getFavicon
    ): void {
        DB::transaction(function () use ($attachOrCreateTags, $getFavicon) {
            foreach ($this->bookmarks as $bookmark) {
                try {
                    // TODO: Implement proper validation

                    $toInsert = [
                        'url' => $bookmark['url'],
                        'title' => $bookmark['title'],
                        'favicon' => $getFavicon->execute($bookmark['url'], 32),
                        'status' => $bookmark['status'] ?? 'active',
                        'user_id' => $this->userId,
                        'imported_at' => now(),
                        'recently_imported' => true,
                    ];

                    $b = Bookmark::create($toInsert);

                    if (isset($bookmark['tags']) && is_array($bookmark['tags'])) {
                        $attachOrCreateTags->execute(bookmark: $b, tags: $bookmark['tags']);
                    }
                } catch (Exception $e) {
                    Log::warning('Failed to import bookmark using ImportBookmarksJob', [
                        'bookmark' => $bookmark,
                        'error' => $e->getMessage(),
                        'user_id' => $this->userId,
                    ]);
                }
            }
        });
    }
}
