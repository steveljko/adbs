<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\UpdateBookmarkAction;
use App\Http\Requests\Bookmark\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\View\View;

final class UpdateBookmarkController
{
    public function __invoke(
        UpdateBookmarkRequest $request,
        UpdateBookmarkAction $action,
        Bookmark $bookmark,
    ) {
        $action->execute(bookmark: $bookmark, data: $request->validated());

        return response()->make(null, 200, ['HX-Trigger' => 'loadBookmarks']);
    }

    public function render(Bookmark $bookmark): View
    {
        $bookmark->load('tags');

        return view('partials.bookmark.edit', compact('bookmark'));
    }
}
