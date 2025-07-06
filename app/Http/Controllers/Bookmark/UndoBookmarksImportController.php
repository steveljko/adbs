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
        return view('resources.bookmark.undo');
    }

    public function confirm(): Response
    {
        $latestImportedAt = Bookmark::query()
            ->whereUserId(Auth::id())
            ->whereNotNull('imported_at')
            ->max('imported_at');

        if ($latestImportedAt) {
            Bookmark::query()
                ->whereUserId(Auth::id())
                ->where('imported_at', $latestImportedAt)
                ->delete();

            return htmx()->toast('success', 'Successfully undo latest import')->response();
        }

        return htmx()->toast('error', "You can't perform action")->response();
    }
}
