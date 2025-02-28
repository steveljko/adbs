<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Requests\Bookmark\CreateBookmarkRequest;
use Illuminate\View\View;

final class CreateBookmarkController
{
    /**
     * Create new bookmark
     */
    public function __invoke(
        CreateBookmarkRequest $request,
        CreateBookmarkAction $action
    ): void {
        $bookmark = $action->execute(data: $request->validated());
    }

    /**
     * Render modal for new boomark creation
     */
    public function render(): View
    {
        return view('resources.bookmark.create');
    }
}
