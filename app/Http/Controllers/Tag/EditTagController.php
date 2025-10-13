<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EditTagController
{
    public function __invoke(Request $request, Tag $tag): View
    {
        $search = $request->string('search')->toString();
        $page = $request->integer('page', 1);

        return view('partials.tag.edit', [
            'tag' => $tag,
            'search' => $search,
            'page' => $page,
        ]);
    }
}
