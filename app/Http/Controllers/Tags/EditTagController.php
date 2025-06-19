<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tags;

use App\Http\Requests\Tag\EditTagRequest;
use App\Models\Tag;

final class EditTagController
{
    public function __invoke(EditTagRequest $request, Tag $tag)
    {
        $tag->update([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'text_color' => $request->text_color,
        ]);
    }
}
