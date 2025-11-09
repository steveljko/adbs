<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Bookmark;
use App\Models\Tag;
use App\ValueObjects\TagAttachOrCreateResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class AttachOrCreateTagsAction
{
    public function __construct(
        private readonly GenerateTagColorsAction $generateTagColorsAction
    ) {}

    /*
    * Attach existing tags or create new ones for the given bookmark.
    */
    public function execute(Bookmark $bookmark, array $tags = []): TagAttachOrCreateResult
    {
        $sanitizedTags = $this->sanitizeTags($tags);

        if ($sanitizedTags->isEmpty()) {
            return new TagAttachOrCreateResult(
                success: false,
                attached: 0,
            );
        }

        $tagIds = $this->getOrCreateTags($sanitizedTags);

        $bookmark->tags()->attach($tagIds);

        Log::info('Attached tags to bookmark', [
            'bookmark_id' => $bookmark->id,
            'tag_ids' => $tagIds,
            'tag_count' => count($tagIds),
            'executed_by' => Auth::id(),
        ]);

        return new TagAttachOrCreateResult(
            success: true,
            attached: count($tagIds)
        );
    }

    /*
     * Return clean collection of valid, unique tag names.
     */
    private function sanitizeTags(array $tags): Collection
    {
        return collect($tags)
            ->filter(fn ($tag) => is_string($tag) && ! empty(mb_trim($tag)))
            ->map(fn ($tag) => mb_trim($tag))
            ->unique()
            ->values();
    }

    private function getOrCreateTags(Collection $tagNames): array
    {
        $existingTags = Tag::whereIn('name', $tagNames)
            ->where('user_id', Auth::id())
            ->pluck('id', 'name');

        $missingTagNames = $tagNames->diff($existingTags->keys());

        if ($missingTagNames->isNotEmpty()) {
            $newTags = $this->createTags($missingTagNames);
            $existingTags = $existingTags->merge($newTags);
        }

        return $existingTags->values()->toArray();
    }

    private function createTags(Collection $tagNames): Collection
    {
        $userId = Auth::id();

        $tagsData = $tagNames->map(function ($name) use ($userId) {
            return [
                'name' => $name,
                'key' => Str::slug($name),
                'text_color' => $this->generateTagColorsAction->getTextColor(),
                'background_color' => $this->generateTagColorsAction->getBackgroundColor(),
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        Tag::insert($tagsData);

        return Tag::whereIn('name', $tagNames)
            ->where('user_id', $userId)
            ->pluck('id', 'name');
    }
}
