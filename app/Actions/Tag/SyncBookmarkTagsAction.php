<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Bookmark;
use App\Models\Tag;
use App\ValueObjects\TagSyncResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class SyncBookmarkTagsAction
{
    public function __construct(
        private readonly AttachOrCreateTagsAction $attachOrCreateTagsAction
    ) {}

    public function execute(Bookmark $bookmark, array $tags = []): TagSyncResult
    {
        $bookmarkTags = $this->getBookmarkTags($bookmark);
        $toAttach = $this->getTagsToAttach($tags, $bookmarkTags);
        $toDetach = $this->getTagsToDetach($tags, $bookmarkTags);

        if ($toAttach->isEmpty() && $toDetach->isEmpty()) {
            return new TagSyncResult(
                success: false,
                attached: [],
                detached: []
            );
        }

        $this->attachNewTags($bookmark, $toAttach);
        $this->detachRemovedTags($bookmark, $toDetach);

        Log::info('Tag sync completed successfully', [
            'bookmark_id' => $bookmark->id,
            'attached' => $toAttach->toArray(),
            'detached' => $toDetach->toArray(),
            'executed_by' => Auth::id(),
        ]);

        return new TagSyncResult(
            success: true,
            attached: $toAttach->toArray(),
            detached: $toDetach->toArray()
        );
    }

    private function getBookmarkTags(Bookmark $bookmark): Collection
    {
        return $bookmark->tags()->pluck('name');
    }

    private function getTagsToAttach(array $toAttach, Collection $bookmarkTags): Collection
    {
        return collect($toAttach)->diff($bookmarkTags);
    }

    private function getTagsToDetach(array $toDetach, Collection $bookmarkTags): Collection
    {
        return $bookmarkTags->diff($toDetach);
    }

    private function attachNewTags(Bookmark $bookmark, Collection $tags): void
    {
        if ($tags->isEmpty()) {
            return;
        }

        $this->attachOrCreateTagsAction->execute(
            bookmark: $bookmark,
            tags: $tags->toArray()
        );
    }

    private function detachRemovedTags(Bookmark $bookmark, Collection $tags): void
    {
        if ($tags->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($bookmark, $tags) {
            $tagModels = Tag::whereUserId(Auth::id())
                ->whereIn('name', $tags)
                ->get();

            foreach ($tagModels as $tag) {
                $bookmark->tags()->detach($tag);
                $this->deleteIfUnused($tag);
            }
        });
    }

    // delete tag if it has no associated bookmarks
    private function deleteIfUnused(Tag $tag): void
    {
        $tag->refresh();

        if ($tag->bookmarks()->doesntExist()) {
            $tag->delete();
        }
    }
}
