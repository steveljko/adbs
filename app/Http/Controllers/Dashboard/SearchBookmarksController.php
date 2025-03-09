<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Str::startsWith($input, 'site:')) {
            return $this->handleDomainSearch(request: $request, input: $input);
        }

        return null;
    }

    public function renderTag(Tag $tag): View
    {
        return view('resources.dashboard.fitlers.tag', ['tag' => $tag->name]);
    }

    public function renderSite(string $site): View
    {
        return view('resources.dashboard.filters.site', ['site' => $site]);
    }

    private function handleTagSearch(Request $request, string $input): View
    {
        $criteria = Str::substr($input, 4);
        $queryTags = $request->input('tags', []);
        $tags = Tag::query()->where('name', 'LIKE', "%$criteria%")->get();

        // Filter out tags that are already selected in the query parameters
        if ($request->tags) {
            $tags = $tags->filter(function ($tag) use ($queryTags) {
                return ! in_array($tag->name, $queryTags);
            });
        }

        return view('resources.dashboard.suggestions', [
            'tags' => $tags,
            'sites' => [],
        ]);
    }

    private function handleDomainSearch(Request $request, string $input)
    {
        $criteria = Str::substr($input, 5);
        $querySites = $request->input('sites', []);
        $sites = Bookmark::query()
            ->whereUserId(Auth::id())
            ->select('url')
            ->get()
            ->map(function ($bookmark) {
                $urlParts = parse_url($bookmark->url);

                return $urlParts['host'];
            })
            ->unique()
            ->filter(function ($site) use ($criteria, $querySites) {
                return mb_strpos($site, $criteria) !== false && ! in_array($site, $querySites);
            });

        return view('resources.dashboard.suggestions', [
            'tags' => [],
            'sites' => $sites,
        ]);
    }
}
