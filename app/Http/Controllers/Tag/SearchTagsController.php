<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class SearchTagsController
{
    public function __invoke(Request $request): Response
    {
        $search = $request->search ?? '';
        $page = $request->integer('page', 1);

        $query = Auth::user()->tags()->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $searchedTags = $query->paginate(10, ['*'], 'page', $page);

        $content = view('partials.settings.tags', [
            'searchedTags' => $searchedTags,
            'search' => $search,
        ])->fragment('content');

        if (! $searchedTags->hasMorePages() && $page === 1) {
            $footerOob = '<div id="card-footer" hx-swap-oob="true" class="hidden"></div>';
        } else {
            $footer = view('partials.settings.tags', [
                'searchedTags' => $searchedTags,
                'search' => $search,
            ])->fragment('load-more');
            $footerOob = '<div id="card-footer" hx-swap-oob="true" class="border-t border-gray-200 px-4 py-2">'.$footer.'</div>';
        }

        return htmx()
            ->target('#content')
            ->swap('innerHTML')
            ->response($content.$footerOob);
    }
}
