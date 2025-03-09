<?php

declare(strict_types=1);

namespace App\Http\Actions\Bookmark;

use App\Http\Actions\Tag\SyncBookmarkTagsAction;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class UpdateBookmarkAction
{
    public function __construct(public SyncBookmarkTagsAction $syncBookmarkTagsAction) {}

    /**
     * Create bookmark
     */
    public function execute(Bookmark $bookmark, array $data): Bookmark
    {
        $bookmark = DB::transaction(function () use ($bookmark, $data) {
            $bookmark->update($data);

            $this->syncBookmarkTagsAction->execute(
                bookmark: $bookmark,
                tags: $data['tags'] ?? []
            );

            return $bookmark;
        });

        Log::info('Bookmark has been updated', [
            'bookmark_id' => $bookmark->id,
            'executed_by' => Auth::id(),
        ]);

        return $bookmark;
    }
}
