<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Actions\Tag\EditTagAction;
use App\Http\Requests\Tag\EditTagRequest;
use App\Models\Tag;
use Illuminate\Http\Response;

final class UpdateTagController
{
    public function __invoke(
        EditTagRequest $request,
        EditTagAction $editTag,
        Tag $tag
    ): Response {
        [$isChanged, $message] = array_values($editTag->execute(request: $request, tag: $tag));

        if (! $isChanged) {
            return htmx()->response();
        }

        return htmx()
            ->trigger('hideModal')
            ->toast(
                type: 'success',
                text: 'Succesfully changed tag details.',
                altText: $message,
                afterSwap: true,
            )
            ->target('#tags')
            ->swap('innerHTML')
            ->response(view('partials.settings.tags'));
    }
}
