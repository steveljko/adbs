<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

final class DeleteTagController
{
    public function __invoke(Tag $tag)
    {
        if (Auth::user()->cannot('delete', $tag)) {
            abort(403);
        }

        $tag->bookmarks()->detach();
        $tag->delete();

        return htmx()
            ->trigger('hideModal')
            ->toast(
                type: 'success',
                text: 'Successfully deleted tag.',
                afterSwap: true,
            )
            ->target('#tags')
            ->swap('innerHTML')
            ->response(view('partials.settings.tags'));
    }
}
