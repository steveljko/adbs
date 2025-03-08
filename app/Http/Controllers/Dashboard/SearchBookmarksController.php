<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class SearchBookmarksController
{
    public function __invoke(Request $request): ?View
    {
        $input = $request->search;

        if (Str::startsWith($input, 'tag:')) {
            return $this->handleTagSearch(request: $request, input: $input);
        }

        return null;
    }

    public function renderTag(Tag $tag): View
    {
        return view('resources.dashboard.tag', ['tag' => $tag->name]);
    }

    private function handleTagSearch(Request $request, string $input): View
    {
        $criteria = Str::substr($input, 4);
        $queryTags = $request->query('tags', []);
        $tags = Tag::query()->where('name', 'LIKE', "%$criteria%")->get();

        // Filter out tags that are already selected in the query parameters
        if ($request->tags) {
            $tags = $tags->filter(function ($tag) use ($queryTags) {
                return ! in_array($tag->name, $queryTags);
            });
        }

        return view('resources.dashboard.suggestions', compact('tags'));
    }
}
