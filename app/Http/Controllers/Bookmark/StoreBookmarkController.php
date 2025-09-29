<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Requests\Bookmark\CreateBookmarkRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class StoreBookmarkController
{
    /**
     * Create new bookmark
     */
    public function __invoke(
        CreateBookmarkRequest $request,
        CreateBookmarkAction $action
    ): Response {
        $action->execute(data: $request->validated(), userId: Auth::id());

        return htmx()->trigger('loadBookmarks')->response();
    }
}
