<?php

declare(strict_types=1);

namespace App\Observers;

use App\Actions\Tag\GenerateBackgroundColorAction;
use App\Models\Tag;
use Illuminate\Support\Str;

final class TagObserver
{
    public function creating(Tag $tag): void
    {
        $tag->key = Str::slug($tag->name);

        $action = app(GenerateBackgroundColorAction::class);
        $tag->background_color = $action->execute($tag->text_color);
    }

    public function updated(Tag $tag): void
    {
        $save = false;

        $newKey = Str::slug($tag->name);

        if ($tag->key !== $newKey) {
            $tag->key = $newKey;
        }

        $action = app(GenerateBackgroundColorAction::class);

        $newBackgroundColor = $action->execute($tag->text_color);
        if ($tag->background_color !== $newBackgroundColor) {
            $tag->background_color = $newBackgroundColor;
            $save = true;
        }

        if ($save) {
            $tag->saveQuietly();
        }
    }
}
