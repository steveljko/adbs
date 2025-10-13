<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Actions\Tag\EditTagAction;
use App\Http\Requests\Tag\EditTagRequest;
use App\Models\Tag;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class UpdateTagController
{
    public function __invoke(
        EditTagRequest $request,
        EditTagAction $editTag,
        Tag $tag
    ): Response {
        [$isChanged, $message] = array_values($editTag->execute($request, $tag));

        if (! $isChanged) {
            return htmx()->response();
        }

        $search = $request->string('search')->toString();
        $page = $request->integer('page', 1);

        $tags = Auth::user()
            ->tags()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        return htmx()
            ->trigger('hideModal')
            ->toast(type: 'success', text: $message)
            ->target('#tags')
            ->swap('outerHTML')
            ->response(view('partials.settings.tags', [
                'tags' => $tags,
                'search' => $search,
                'page' => $page,
            ]));
    }
}
