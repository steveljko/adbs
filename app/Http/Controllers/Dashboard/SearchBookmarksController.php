<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class SearchBookmarksController
{
    public function __invoke(Request $request): Response
    {
        $input = $request->input('search', null);

        if (Str::startsWith($input, '/')) {
            if (Str::startsWith($input, '/tag:')) {
                return $this->handleTagSearch(request: $request, input: $input);
            }

            if (Str::startsWith($input, '/site:')) {
                return $this->handleDomainSearch(request: $request, input: $input);
            }

            return htmx()
                ->target('#suggestions-container')
                ->swap('innerHTML')
                ->response('');
        }

        return htmx()
            ->target('#title')
            ->swap('outerHTML')
            ->triggerAfterSwap('loadBookmarks')
            ->response(view('resources.dashboard.filters.title', ['title' => $input]));
    }

    public function renderTag(Tag $tag): View
    {
        return view('resources.dashboard.filters.tag', compact('tag'));
    }

    public function renderSite(string $site): View
    {
        return view('resources.dashboard.filters.site', ['site' => $site]);
    }

    private function handleTagSearch(Request $request, string $input): Response
    {
        $criteria = Str::substr($input, 5);
        $queryTags = $request->input('tags', []);
        $tags = Tag::query()->where('name', 'LIKE', "%$criteria%")->get();

        // Filter out tags that are already selected in the query parameters
        if ($request->tags) {
            $tags = $tags->filter(function ($tag) use ($queryTags) {
                return ! in_array($tag->name, $queryTags);
            });
        }

        return htmx()
            ->target('#suggestions-container')
            ->swap('innerHTML')
            ->response(view('resources.dashboard.suggestions', [
                'tags' => $tags,
                'sites' => [],
            ]));
    }

    private function handleDomainSearch(Request $request, string $input): Response
    {
        $criteria = Str::substr($input, 6);
        $querySites = $request->input('sites', []);
        $sites = Bookmark::query()
            ->whereUserId(Auth::id())
            ->select('url')
            ->get()
            ->map(function ($bookmark) {
                $urlParts = parse_url($bookmark->url);
                $host = $urlParts['host'];

                // remove 'www.' from the beginning if present
                if (mb_strpos($host, 'www.') === 0) {
                    $host = mb_substr($host, 4);
                }

                return $host;
            })
            ->unique()
            ->filter(function ($site) use ($criteria, $querySites) {
                return mb_strpos($site, $criteria) !== false && ! in_array($site, $querySites);
            });

        return htmx()
            ->target('#suggestions-container')
            ->swap('innerHTML')
            ->response(view('resources.dashboard.suggestions', [
                'tags' => [],
                'sites' => $sites,
            ]));
    }
}
