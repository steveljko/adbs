<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Models\Bookmark;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class DestroyBookmarkController
{
    public function __invoke(Bookmark $bookmark): Response
    {
        if (Auth::user()->cannot('delete', $bookmark)) {
            abort(403);
        }

        $bookmark->delete();

        return htmx()->trigger('loadBookmarks')->response();
    }
}
