<?php

declare(strict_types=1);

namespace App\Actions\Bookmark;

use App\Actions\Tag\AttachOrCreateTagsAction;
use App\Actions\Website\GetFaviconAction;
use App\Enums\BookmarkStatus;
use App\Models\Bookmark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class CreateBookmarkAction
{
    public function __construct(
        public AttachOrCreateTagsAction $attachOrCreateTagsAction,
        public GetFaviconAction $getFaviconAction
    ) {}

    /**
     * Create bookmark
     */
    public function execute(array $data, int $userId): Bookmark
    {
        if (! isset($data['favicon'])) {
            try {
                $data['favicon'] = $this->getFaviconAction->execute($data['url'], 32);
            } catch (RuntimeException $e) {
                Log::warning('Failed to fetch favicon', ['url' => $data['url'], 'error' => $e->getMessage()]);

                $data['favicon'] = null;
            }
        }

        $data = array_merge($data, [
            'status' => BookmarkStatus::PUBLISHED,
            'user_id' => $userId,
        ]);

        $bookmark = DB::transaction(function () use ($data) {
            $bookmark = Bookmark::create($data);

            $this->attachOrCreateTagsAction->execute(
                bookmark: $bookmark,
                tags: $data['tags'] ?? []
            );

            return $bookmark;
        });

        Log::info('New bookmark has been created', [
            'bookmark_id' => $bookmark->id,
            'executed_by' => $userId,
        ]);

        return $bookmark;
    }
}
