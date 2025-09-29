<?php

declare(strict_types=1);

namespace App\Actions\Bookmark;

use App\Actions\Tag\SyncBookmarkTagsAction;
use App\Models\Bookmark;
use Illuminate\Support\Facades\DB;

final class UpdateBookmarkAction
{
    public function __construct(public SyncBookmarkTagsAction $syncBookmarkTagsAction) {}

    /**
     * Update bookmark
     */
    public function execute(Bookmark $bookmark, array $data): array
    {
        return DB::transaction(function () use ($bookmark, $data) {
            $tagsChanged = $this->syncBookmarkTagsAction->execute(
                bookmark: $bookmark,
                tags: $data['tags'] ?? []
            );

            if ($tagsChanged === true) {
                $bookmark->withAdditionalFields(['tags']);
            }

            return $bookmark->updateAndRespond(data: $data, additionalFields: ['tags']);
        });
    }
}
