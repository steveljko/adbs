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
        $search = $request->string('search')->toString();
        $page = $request->integer('page', 1);

        $tags = Auth::user()
            ->tags()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        return htmx()->response(
            view('partials.settings.tags', [
                'tags' => $tags,
                'search' => $search,
                'page' => $page,
            ])
        );
    }
}
