<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Models\Bookmark;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class UndoBookmarksImportController
{
    public function __invoke(): View
    {
        return view('partials.bookmark.import-export.undo');
    }

    public function confirm(): Response
    {
        $deletedCount = Bookmark::latestImportForUser(Auth::user())->delete();

        return $deletedCount > 0
            ? htmx()->toast('success', 'Successfully undid latest import')->response()
            : htmx()->toast('error', "You can't perform this action")->response();
    }
}
