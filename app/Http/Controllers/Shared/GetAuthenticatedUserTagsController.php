<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetAuthenticatedUserTagsController
{
    public function __invoke(Request $request): View
    {
        $name = $request->query('name', '');
        $queryTags = $request->query('tags', []);

        $tags = Tag::query()
            ->where('name', 'LIKE', "%$name%")
            ->get()
            ->filter(function ($tag) use ($queryTags) {
                return ! in_array($tag->name, $queryTags);
            });

        return view('partials.tag.tags-suggestions', compact('tags', 'name'));
    }

    public function renderTag(string $tag): View
    {
        return view('partials.tag.unstored-tag', compact('tag'));
    }
}
