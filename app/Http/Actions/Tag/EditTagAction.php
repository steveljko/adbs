<?php

declare(strict_types=1);

namespace App\Http\Actions\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;

final class EditTagAction
{
    public function execute(Request $request, Tag $tag): array
    {
        return $tag->updateAndRespond(data: [
            'name' => $request->name,
            'description' => $request->description ?? null,
            'text_color' => $request->text_color,
        ], overrideName: ['text_color' => 'color']);
    }
}
