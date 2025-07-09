<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Models\Bookmark;
use Illuminate\Http\Response;

final class DestroyBookmarkController
{
    public function __invoke(Bookmark $bookmark): Response
    {
        $bookmark->delete();

        return htmx()->trigger('loadBookmarks')->response();
    }
}
