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

    public function execute(Bookmark $bookmark, array $tags = []): bool
    {
        $bookmarkTags = $bookmark->tags()->pluck('name')->toArray();
        $tagsToAttach = array_diff($tags, $bookmarkTags);
        $tagsToDelete = array_diff($bookmarkTags, $tags);

        $changed = false;

        if (count($tagsToAttach) >= 1) {
            $this->attachOrCreateTagsAction->execute(bookmark: $bookmark, tags: $tagsToAttach);
            $changed = true;
        }

        if (count($tagsToDelete) >= 1) {
            $this->detachTags(bookmark: $bookmark, tagsToDelete: $tagsToDelete);
            $changed = true;
        }

        return $changed;
    }

    private function detachTags(Bookmark $bookmark, array $tagsToDelete): void
    {
        DB::transaction(function () use ($bookmark, $tagsToDelete) {
            foreach ($tagsToDelete as $tag) {
                $tagModel = Tag::whereUserId(Auth::id())->where('name', $tag)->first();
                if ($tagModel) {
                    $bookmark->tags()->detach($tagModel);
                    if ($tagModel->bookmarks()->count() === 0) {
                        $tagModel->delete();
                    }
                }
            }
        });
    }
}
