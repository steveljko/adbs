<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Actions\Bookmark\UpdateBookmarkAction;
use App\Http\Requests\Bookmark\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class UpdateBookmarkController
{
    public function __invoke(
        UpdateBookmarkRequest $request,
        UpdateBookmarkAction $updateBookmark,
        Bookmark $bookmark,
    ): Response {
        if (Auth::user()->cannot('update', $bookmark)) {
            abort(403);
        }

        [$isChanged, $message] = array_values($updateBookmark->execute(
            bookmark: $bookmark,
            data: $request->validated()
        ));

        if (! $isChanged) {
            return htmx()->response();
        }

        return htmx()
            ->trigger('loadBookmarks')
            ->toast(type: 'success', text: 'Bookmark successfully updated!', altText: $message)
            ->response();
    }
}
