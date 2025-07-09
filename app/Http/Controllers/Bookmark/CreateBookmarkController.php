<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use Illuminate\View\View;

final class CreateBookmarkController
{
    /**
     * Render modal for new boomark creation
     */
    public function __invoke(): View
    {
        return view('partials.bookmark.create');
    }
}
