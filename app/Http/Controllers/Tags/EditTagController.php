<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tags;

use App\Http\Requests\Tag\EditTagRequest;
use App\Models\Tag;

final class EditTagController
{
    public function __invoke(EditTagRequest $request, Tag $tag)
    {
        $displayNames = [
            'name' => 'name',
            'description' => 'description',
            'text_color' => 'color',
        ];

        $tag->fill([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'text_color' => $request->text_color,
        ]);

        if (! $tag->isDirty()) {
            return htmx()->response(null);
        }

        $changedFields = array_keys($tag->getDirty());

        $changes = array_map(fn ($field) => $displayNames[$field] ?? $field, $changedFields);

        $tag->save();

        $altText = match (count($changes)) {
            0 => 'No changes were made to the tag.',
            1 => "Tag {$changes[0]} has been updated.",
            2 => "Tag {$changes[0]} and {$changes[1]} have been updated.",
            default => 'Tag '.implode(', ', array_slice($changes, 0, -1)).' and '.end($changes).' have been updated.'
        };

        // TODO: do localization for this messages.
        return htmx()
            ->trigger('hideModal')
            ->toast(
                type: 'success',
                text: 'Succesfully changed tag details.',
                altText: $altText,
                afterSwap: true,
            )
            ->target('#tags')
            ->swap('innerHTML')
            ->response(view('partials.tag.show'));
    }
}
