<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Requests\Bookmark\CreateBookmarkRequest;
use Illuminate\Http\Response;

final class StoreBookmarkController
{
    /**
     * Create new bookmark
     */
    public function __invoke(
        CreateBookmarkRequest $request,
        CreateBookmarkAction $action
    ): Response {
        $action->execute(data: $request->validated());

        return htmx()->trigger('loadBookmarks')->response();
    }
}
