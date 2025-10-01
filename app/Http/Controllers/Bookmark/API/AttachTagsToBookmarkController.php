<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark\API;

use App\Actions\Tag\AttachOrCreateTagsAction;
use App\Models\Bookmark;
use Illuminate\Http\Request;

final class AttachTagsToBookmarkController
{
    public function __invoke(
        Request $request,
        Bookmark $bookmark,
        AttachOrCreateTagsAction $attachOrCreateTags
    ): void {
        $request->validate([
            'tags' => 'required|array',
        ]);

        $attachOrCreateTags->execute(
            bookmark: $bookmark,
            tags: $data['tags'] ?? []
        );
    }
}
