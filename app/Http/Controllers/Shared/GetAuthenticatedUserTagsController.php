<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class GetAuthenticatedUserTagsController
{
    public function __invoke(Request $request): View
    {
        $name = $request->query('name', '');
        $queryTags = $request->query('tags', []);

        $tags = Tag::query()
            ->where('user_id', Auth::id())
            ->where('name', 'LIKE', "%$name%")
            ->get()
            ->filter(function ($tag) use ($queryTags) {
                return ! in_array($tag->name, $queryTags);
            });

        return view('partials.tag.tags-suggestions', compact('tags', 'name'));
    }

    public function renderTag(string $name): View
    {
        $tag = Tag::where('user_id', Auth::id())
            ->where('name', $name)
            ->first();

        if ($tag) {
            return view('partials.dashboard.filters.tag', compact('tag'));
        }

        return view('partials.tag.unstored-tag', ['tag' => $name]);
    }
}
