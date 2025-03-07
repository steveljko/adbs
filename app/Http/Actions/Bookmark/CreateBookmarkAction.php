<?php

declare(strict_types=1);

namespace App\Http\Actions\Bookmark;

use App\Enums\BookmarkStatus;
use App\Http\Actions\Tag\AttachOrCreateTagsAction;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CreateBookmarkAction
{
    public function __construct(public AttachOrCreateTagsAction $attachOrCreateTagsAction) {}

    /**
     * Create bookmark
     */
    public function execute(array $data): Bookmark
    {
        $data = array_merge($data, [
            'status' => BookmarkStatus::PUBLISHED,
            'user_id' => Auth::id(),
        ]);

        $bookmark = DB::transaction(function () use ($data) {
            $bookmark = Bookmark::create($data);

            $this->attachOrCreateTagsAction->execute(
                bookmark: $bookmark,
                tags: $data['tags'] ?? []
            );

            return $bookmark;
        });

        Log::info('New bookmark has been created', [
            'bookmark_id' => $bookmark->id,
            'executed_by' => Auth::id(),
        ]);

        return $bookmark;
    }
}
