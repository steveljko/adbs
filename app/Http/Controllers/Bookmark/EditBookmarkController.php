<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Models\Bookmark;
use Illuminate\View\View;

final class EditBookmarkController
{
    public function __invoke(Bookmark $bookmark): View
    {
        $bookmark->load('tags');

        return view('partials.bookmark.edit', compact('bookmark'));
    }
}
