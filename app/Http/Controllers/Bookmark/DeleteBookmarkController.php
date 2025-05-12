<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Models\Bookmark;
use Illuminate\View\View;

final class DeleteBookmarkController
{
    public function __invoke(Bookmark $bookmark)
    {
        $bookmark->delete();

        return response()->make(null, 200, ['HX-Trigger' => 'loadBookmarks']);
    }

    /**
     * Render modal for new boomark creation
     */
    public function render(Bookmark $bookmark): View
    {
        return view('resources.bookmark.delete', compact('bookmark'));
    }
}
