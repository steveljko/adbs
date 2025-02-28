<?php

declare(strict_types=1);

namespace App\Http\Actions\Bookmark;

use App\Enums\BookmarkStatus;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

final class CreateBookmarkAction
{
    /**
     * Create bookmark
     */
    public function execute(array $data): Bookmark
    {
        $data = array_merge($data, [
            'status' => BookmarkStatus::PUBLISHED,
            'user_id' => Auth::user()->id,
        ]);

        return Bookmark::create($data);
    }
}
