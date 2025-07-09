<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Models\Tag;
use Illuminate\View\View;

final class EditTagController
{
    public function __invoke(Tag $tag): View
    {
        return view('partials.tag.edit', ['tag' => $tag]);
    }
}
