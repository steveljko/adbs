<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class DeleteTagController
{
    public function __invoke(Request $request, Tag $tag): Response
    {
        if (Auth::user()->cannot('delete', $tag)) {
            abort(403);
        }

        $search = $request->string('search')->toString();
        $page = $request->integer('page', 1);

        $tag->bookmarks()->detach();
        $tag->delete();

        $tags = Auth::user()
            ->tags()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        return htmx()
            ->trigger('hideModal')
            ->toast(type: 'success', text: 'Successfully deleted tag.')
            ->target('#tags')
            ->swap('innerHTML')
            ->response(
                view('partials.settings.tags', [
                    'tags' => $tags,
                    'search' => $search,
                    'page' => $page,
                ])
            );
    }
}
