<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mauricius\LaravelHtmx\Http\HtmxRequest;

final class ShowDashboardController
{
    public function __invoke(HtmxRequest $request): View|RedirectResponse|string
    {
        $queryTags = $request->query('tags', []);
        $querySites = $request->query('sites', []);
        $title = $request->query('title', null);
        $viewType = $request->query('view_type', 'card');

        $tags = $this->getAvailableTags();
        $sites = $this->getAvailableSites();

        $this->validateQueryParams(
            request: $request,
            availableTags: $tags,
            availableSites: $sites,
        );

        $bookmarks = Bookmark::query()
            ->withTagsAndSites(tags: $queryTags, sites: $querySites)
            ->where('title', 'LIKE', "%{$title}%")
            ->latest()
            ->get();

        if ($request->isHtmxRequest()) {
            return view('components.bookmark-list', [
                'type' => $viewType,
                'bookmarks' => $bookmarks,
            ])->render();
        }

        return view('resources.dashboard.home', compact('queryTags', 'querySites', 'viewType', 'tags', 'bookmarks'));
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

                return $urlParts['host'];
            })
            ->unique()
            ->toArray();
    }
}
