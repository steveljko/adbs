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
    private const PER_PAGE = 30;

    public function __invoke(Request $request): View|RedirectResponse|string
    {
        $filters = $this->extractFilters($request);
        $pagination = $this->extractPagination($request);

        $this->validateQueryParams($request);

        $bookmarksData = $this->getBookmarksData($filters, $pagination);

        if ($request->expectsJson() || htmx()->isRequest()) {
            return $this->handleHtmxRequest($request, $filters['viewType'], $bookmarksData, $pagination, $filters);
        }

        return $this->renderFullPage($filters, $bookmarksData, $pagination);
    }

    private function extractFilters(Request $request): array
    {
        $queryTags = $request->query('tags', []);
        $querySites = $request->query('sites', []);

        // convert tag names to Tag models
        $tagModels = array_filter(array_map(
            fn ($tagName) => Tag::where('name', $tagName)->first(),
            $queryTags
        ));

        return [
            'tags' => $tagModels,
            'sites' => $querySites,
            'title' => $request->query('title'),
            'viewType' => $request->query('view_type', 'card'),
        ];
    }

    private function extractPagination(Request $request): array
    {
        $page = max(1, (int) $request->query('page', 1));
        $offset = ($page - 1) * self::PER_PAGE;

        return [
            'page' => $page,
            'perPage' => self::PER_PAGE,
            'offset' => $offset,
        ];
    }

    private function getBookmarksData(array $filters, array $pagination): array
    {
        $query = Bookmark::query()
            ->withTagsAndSites($filters['tags'], $filters['sites'])
            ->whereUserId(Auth::id())
            ->latest();

        // apply title filter if provided
        if ($filters['title']) {
            $query->where('title', 'LIKE', '%'.addcslashes($filters['title'], '%_\\').'%');
        }

        $totalCount = $query->count();
        $bookmarks = $query->offset($pagination['offset'])
            ->limit($pagination['perPage'])
            ->get();

        $hasMore = $totalCount > ($pagination['offset'] + $pagination['perPage']);

        return [
            'bookmarks' => $bookmarks,
            'totalCount' => $totalCount,
            'hasMore' => $hasMore,
            'nextPage' => $hasMore ? $pagination['page'] + 1 : null,
        ];
    }

    private function handleHtmxRequest(
        Request $request,
        string $viewType,
        array $bookmarksData,
        array $pagination,
        array $filters
    ): string {
        $data = [
            'type' => $viewType,
            'bookmarks' => $bookmarksData['bookmarks'],
            'hasMore' => $bookmarksData['hasMore'],
            'nextPage' => $bookmarksData['nextPage'],
            'currentPage' => $pagination['page'],
            'queryTags' => $filters['tags'],
            'querySites' => $filters['sites'],
            'title' => $filters['title'],
        ];

        if ($request->has('load_more')) {
            $bookmarks = view('components.bookmarks', $data)->fragment('bookmarks');
            $loadMore = view('components.loadmore', $data)->render();

            return $bookmarks.$loadMore;
        }

        $bookmarks = view('components.bookmarks', array_merge($data, ['showSwitch' => 'true']))->render();
        $loadMore = view('components.loadmore', $data)->render();

        return $bookmarks.$loadMore;
    }

    private function renderFullPage(array $filters, array $bookmarksData, array $pagination): View
    {
        return view('pages.dashboard.home', [
            'queryTags' => $filters['tags'],
            'querySites' => $filters['sites'],
            'viewType' => $filters['viewType'],
            'title' => $filters['title'],
            'tags' => Tag::getForUser(),
            'bookmarks' => $bookmarksData['bookmarks'],
            'hasMore' => $bookmarksData['hasMore'],
            'nextPage' => $bookmarksData['nextPage'],
            'currentPage' => $pagination['page'],
        ]);
    }

    private function validateQueryParams(Request $request): void
    {
        $queryTags = $request->query('tags', []);
        $querySites = $request->query('sites', []);

        if (empty($queryTags) && empty($querySites)) {
            return;
        }

        $availableTags = Tag::getForUser();
        $availableSites = Bookmark::getSitesForUser();

        $invalidTags = array_diff($queryTags, $availableTags);
        $invalidSites = array_diff($querySites, $availableSites);

        if (! empty($invalidTags) || ! empty($invalidSites)) {
            abort(403, 'Invalid tags or site URLs provided.');
        }
    }
}
