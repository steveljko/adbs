<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mauricius\LaravelHtmx\Http\HtmxRequest;

final class ShowDashboardController
{
    public function __invoke(HtmxRequest $request): View|RedirectResponse|string
    {
        $queryTags = $request->query('tags', []);

        $tags = Tag::query()
            ->whereUserId(Auth::id())
            ->pluck('name')
            ->toArray();

        // Check if invalid tags are provided
        if ($invalidTags = array_diff($queryTags, $tags)) {
            session()->flash('message', 'Invalid tags provided: '.implode(', ', $invalidTags));

            return back();
        }

        $bookmarks = Bookmark::when($queryTags, function ($query) use ($queryTags) {
            $query->whereHas('tags', function ($query) use ($queryTags) {
                $query->whereIn('name', $queryTags);
            });
        })->orderBy('created_at', 'desc')->get();

        if ($request->isHtmxRequest()) {
            return view()->renderFragment('resources.dashboard.home', 'bookmark-list', compact('queryTags', 'tags', 'bookmarks'));
        }

        return view('resources.dashboard.home', compact('queryTags', 'tags', 'bookmarks'));
    }
}
