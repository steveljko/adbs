<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Website\GetFaviconAction;
use App\Http\Actions\Website\GetTitleAction;
use App\Http\Requests\Bookmark\PreviewBookmarkRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class PreviewBookmarkController
{
    public function __invoke(
        PreviewBookmarkRequest $request,
        GetTitleAction $getTitleAction,
        GetFaviconAction $getFaviconAction,
    ): View {
        $title = $getTitleAction->execute(url: $request->url);
        $favicon = $getFaviconAction->execute(url: $request->url, size: 32);
        $tags = Auth::user()->tags()->pluck('name')->toArray();

        return view('resources.bookmark.preview', [
            'title' => $title,
            'favicon' => $favicon,
            'tags' => $tags,
        ]);
    }

    public function tagSuggest(Request $request)
    {
        $search = $request->query('search', '');
        $tags = Tag::whereUserId(Auth::id())
            ->get()
            ->filter(function ($tag) use ($search) {
                return empty($search) || mb_stripos($tag->name, $search) !== false;
            });

        return view('resources.bookmark.tags-suggestions', compact('tags'));
    }
}
