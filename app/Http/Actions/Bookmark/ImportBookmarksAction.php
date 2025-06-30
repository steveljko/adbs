<?php

declare(strict_types=1);

namespace App\Http\Actions\Bookmark;

use App\Exceptions\Bookmark\Import\EmptyBookmarkFileException;
use App\Http\Actions\Tag\AttachOrCreateTagsAction;
use App\Http\Actions\Website\GetFaviconAction;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidBookmarkDataException;

final class ImportBookmarksAction
{
    public function __construct(
        private AttachOrCreateTagsAction $attachOrCreateTags,
        private GetFaviconAction $getFavicon
    ) {}

    public function execute($file)
    {
        $content = file_get_contents($file->getRealPath());
        $bookmarks = json_decode($content, true);

        if (! is_array($bookmarks)) {
            throw new InvalidBookmarkDataException('JSON file must contain array of bookmarks.');
        }

        if (count($bookmarks) < 1) {
            throw new EmptyBookmarkFileException;
        }

        $imported = 0;
        $userId = Auth::id();

        DB::transaction(function () use ($bookmarks, $userId, &$imported) {
            foreach ($bookmarks as $bookmark) {
                $toInsert = [
                    'url' => $bookmark['url'],
                    'title' => $bookmark['title'],
                    'favicon' => $this->getFavicon->execute($bookmark['url'], 32),
                    'status' => $bookmark['status'],
                    'user_id' => $userId,
                ];

                $b = Bookmark::create($toInsert);

                if (isset($bookmark['tags'])) {
                    $this->attachOrCreateTags->execute(bookmark: $b, tags: $bookmark['tags']);
                }

                $imported++;
            }
        });

        return $imported;
    }
}
