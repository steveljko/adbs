<?php

declare(strict_types=1);

namespace App\Http\Actions\Tag;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class SyncBookmarkTagsAction
{
    public function __construct(private AttachOrCreateTagsAction $attachOrCreateTagsAction) {}

    public function execute(Bookmark $bookmark, array $tags = []): void
    {
        $bookmarkTags = $bookmark->tags()->pluck('name')->toArray();

        $tagsToAttach = array_diff($tags, $bookmarkTags);

        if (count($tagsToAttach) >= 1) {
            $this->attachOrCreateTagsAction->execute(bookmark: $bookmark, tags: $tagsToAttach);
        }

        $this->deatachTags(
            bookmark: $bookmark,
            bookmarkTags: $bookmarkTags,
            tags: $tags
        );
    }

    private function deatachTags(
        Bookmark $bookmark,
        array $bookmarkTags,
        array $tags
    ): void {
        $tagsToDelete = array_diff($bookmarkTags, $tags);

        DB::transaction(function () use ($bookmark, $tagsToDelete) {
            foreach ($tagsToDelete as $tag) {
                $tagModel = Tag::whereUserId(Auth::id())->where('name', $tag)->first();
                $bookmark->tags()->detach($tagModel);

                if ($tagModel->bookmarks()->count() === 0) {
                    $tagModel->delete();
                }
            }
        });
    }
}
