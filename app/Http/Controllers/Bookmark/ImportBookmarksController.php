<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Exceptions\Bookmark\Import\BookmarkImportException;
use App\Http\Actions\Bookmark\ImportBookmarksAction;
use App\Http\Requests\Bookmark\ImportBookmarksRequest;
use Illuminate\Support\Facades\Log;

final class ImportBookmarksController
{
    public function __invoke(
        ImportBookmarksRequest $request,
        ImportBookmarksAction $importBookmarks,
    ) {
        try {
            $imported = $importBookmarks->execute($request->file);

            return htmx()
                ->toast(
                    type: 'success',
                    text: 'Successfully imported bookmarks!',
                    altText: "$imported bookmarks are imported.",
                )->response(null);
        } catch (BookmarkImportException $e) {
            Log::warning('Bookmark import failed', [
                'error' => $e->getMessage(),
                'exception_type' => get_class($e),
                'user_id' => auth()->id(),
            ]);

            return htmx()
                ->toast(
                    type: 'error',
                    text: $e->getMessage(),
                )->response(null);
        }
    }
}
