<?php

declare(strict_types=1);

namespace App\Http\Actions\Tag;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class AttachOrCreateTagsAction
{
    private array $colors = [
        '#e74c3c',
        '#2ecc71',
        '#3498db',
        '#f1c40f',
        '#9b59b6',
        '#e67e22',
        '#1abc9c',
        '#34495e',
        '#95a5a6',
        '#ecf0f1',
        '#d35400',
        '#c0392b',
        '#27ae60',
        '#2980b9',
        '#8e44ad',
        '#f39c12',
        '#2c3e50',
    ];

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
                $textColor = $this->colors[array_rand($this->colors)];
                $bgColor = $this->getBackgroundColor($textColor);

                if (! $tag) {
                    $tag = Tag::create([
                        'name' => $name,
                        'key' => Str::slug($name),
                        'background_color' => $bgColor,
                        'text_color' => $textColor,
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

    private function getBackgroundColor(string $color): string
    {
        $lightness = (float) 0.95;
        $hexColor = mb_ltrim($color, '#');

        if (mb_strlen($hexColor) === 3) {
            $hexColor = $hexColor[0].$hexColor[0].
                $hexColor[1].$hexColor[1].
                $hexColor[2].$hexColor[2];
        }

        $r = hexdec(mb_substr($hexColor, 0, 2));
        $g = hexdec(mb_substr($hexColor, 2, 2));
        $b = hexdec(mb_substr($hexColor, 4, 2));

        // apply lightness by blending with white
        $r = (int) round($r + ($lightness * (255 - $r)));
        $g = (int) round($g + ($lightness * (255 - $g)));
        $b = (int) round($b + ($lightness * (255 - $b)));

        // ensure values are within valid range
        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));

        // convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
