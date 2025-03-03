<?php

declare(strict_types=1);

namespace App\Http\Actions\Tag;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// TODO: Generate random hex color for tag colour
final class AttachOrCreateTagsAction
{
    /*
    * Attach existing tags or create new ones for the given bookmark.
    */
    public function execute(Bookmark $bookmark, array $tags = []): void
    {
        $tagsToAttach = [];

        if (! empty($tags)) {
            foreach ($tags as $name) {
                if (empty($name) || ! is_string($name)) {
                    continue;
                }

                $tag = Tag::where('name', $name)->first();

                if (! $tag) {
                    $tag = Tag::create([
                        'name' => $name,
                        'key' => Str::slug($name),
                        'hex_color' => '#cf000f',
                        'user_id' => Auth::id(),
                    ]);
                }

                $tagsToAttach[] = $tag->id;
            }
        }

        if (! empty($tagsToAttach)) {
            $bookmark->tags()->attach($tagsToAttach);
        }

        Log::info('Attached tags to bookmark', [
            'tags' => $tagsToAttach,
            'bookmark_id' => $bookmark->id,
            'executed_by' => Auth::id(),
        ]);
    }
}
