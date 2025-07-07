<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(Request $request): View|RedirectResponse|string
    {
        $queryTags = $request->query('tags', []);
        $querySites = $request->query('sites', []);

        $queryTags = array_map(function ($tag) {
            return Tag::where('name', $tag)->first();
        }, $queryTags);

        $title = $request->query('title', null);
        $viewType = $request->query('view_type', 'card');

        $page = (int) $request->query('page', 1);
        $perPage = 35;
        $offset = ($page - 1) * $perPage;

        $tags = $this->getAvailableTags();
        $sites = $this->getAvailableSites();

        $this->validateQueryParams(
            request: $request,
            availableTags: $tags,
            availableSites: $sites,
        );

        $bookmarksQuery = Bookmark::query()
            ->withTagsAndSites(tags: $queryTags, sites: $querySites)
            ->where('title', 'LIKE', "%{$title}%")
            ->whereUserId(Auth::id())
            ->latest();

        $totalCount = $bookmarksQuery->count();
        $bookmarks = $bookmarksQuery->offset($offset)->limit($perPage)->get();

        $hasMore = $totalCount > ($offset + $perPage);
        $nextPage = $hasMore ? $page + 1 : null;

        if (htmx()->isRequest()) {
            if ($request->has('load_more')) {
                return view('partials.bookmark.bookmark-items', [
                    'type' => $viewType,
                    'bookmarks' => $bookmarks,
                    'hasMore' => $hasMore,
                    'nextPage' => $nextPage,
                    'currentPage' => $page,
                ])->render();
            }

            return view('components.bookmark-list', [
                'type' => $viewType,
                'bookmarks' => $bookmarks,
                'hasMore' => $hasMore,
                'nextPage' => $nextPage,
                'currentPage' => $page,
            ])->render();
        }

        return view('resources.dashboard.home', [
            'queryTags' => $queryTags,
            'querySites' => $querySites,
            'viewType' => $viewType,
            'tags' => $tags,
            'bookmarks' => $bookmarks,
            'hasMore' => $hasMore,
            'nextPage' => $nextPage,
            'currentPage' => $page,
        ]);
    }

    /**
     * Validate URL query parameters
     */
    private function validateQueryParams(
        Request $request,
        array $availableTags,
        array $availableSites
    ) {
        $invalidTags = array_diff($request->query('tags', []), $availableTags);
        $invalidSites = array_diff($request->query('sites', []), $availableSites);

        if ($invalidTags || $invalidSites) {
            abort(403, 'Invalid tags or site URLs are provided.');
        }
    }

    /**
     * Get all tags for authenticated user
     */
    private function getAvailableTags(): array
    {
        return Tag::query()
            ->whereUserId(Auth::id())
            ->pluck('name')
            ->toArray();
    }

    /**
     * Get all sites for authenticated user
     * TODO: Cache website urls
     */
    private function getAvailableSites(): array
    {
        return Bookmark::query()
            ->whereUserId(Auth::id())
            ->pluck('url')
            ->map(function ($url) {
                $urlParts = parse_url($url);
                $host = $urlParts['host'];

                // remove 'www.' from the beginning if present
                if (mb_strpos($host, 'www.') === 0) {
                    $host = mb_substr($host, 4);
                }

                return $host;
            })
            ->unique()
            ->toArray();
    }
}
