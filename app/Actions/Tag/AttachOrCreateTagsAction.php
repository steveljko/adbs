<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Bookmark;
use App\Models\Tag;
use App\ValueObjects\TagAttachOrCreateResult;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class AttachOrCreateTagsAction
{
    public function __construct(
        private readonly GenerateTagColorsAction $generateTagColorsAction
    ) {}

    public function execute(Bookmark $bookmark, array $tags = []): TagAttachOrCreateResult
    {
        $sanitizedTags = $this->sanitizeTags($tags);

        if ($sanitizedTags->isEmpty()) {
            return new TagAttachOrCreateResult(
                success: false,
                attached: 0,
            );
        }

        $tagIds = $this->getOrCreateTags($sanitizedTags, $bookmark->user_id);
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

    private function sanitizeTags(array $tags): Collection
    {
        return collect($tags)
            ->filter(fn ($tag) => is_string($tag) && ! empty(mb_trim($tag)))
            ->map(fn ($tag) => mb_trim($tag))
            ->unique()
            ->values();
    }

    private function getOrCreateTags(Collection $tagNames, int $userId): array
    {
        $tagIds = [];

        foreach ($tagNames as $name) {
            $key = Str::slug($name);

            Log::debug('Processing tag', [
                'name' => $name,
                'key' => $key,
                'user_id' => $userId,
            ]);

            $tag = DB::transaction(function () use ($key, $name, $userId) {
                $tag = Tag::where('key', $key)
                    ->where('user_id', $userId)
                    ->lockForUpdate()
                    ->first();

                if ($tag) {
                    Log::debug('Tag exists', ['tag_id' => $tag->id, 'user_id' => $tag->user_id]);

                    return $tag;
                }

                try {
                    $tag = new Tag([
                        'name' => $name,
                        'key' => $key,
                        'text_color' => $this->generateTagColorsAction->getTextColor(),
                        'background_color' => $this->generateTagColorsAction->getBackgroundColor(),
                        'user_id' => $userId,
                    ]);

                    $tag->save();

                    Log::debug('Tag created', ['tag_id' => $tag->id]);

                    return $tag;

                } catch (Exception $e) {
                    Log::error('Tag creation failed', [
                        'key' => $key,
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                        'code' => $e->getCode(),
                    ]);

                    throw $e;
                }
            });

            $tagIds[] = $tag->id;
        }

        return $tagIds;
    }
}
