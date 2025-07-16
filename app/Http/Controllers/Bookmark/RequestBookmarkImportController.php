<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\ImportBookmarksAction;
use App\Http\Requests\Bookmark\ImportBookmarksRequest;
use App\Support\TempFileManager;
use Illuminate\Http\Response;

/**
 * This controller is responsible for requesting import.
 * It detects if the file is encrypted or not,
 * and sends the user the right modal via HTMX.
 *
 * If the file is encrypted, it sends prompt to enter the password,
 * decrypts, and imports bookmarks.
 *
 * If not, it shows modal to confirm the import.
 */
final class RequestBookmarkImportController
{
    public function __invoke(
        ImportBookmarksRequest $request,
        ImportBookmarksAction $importBookmarks,
        TempFileManager $tempFile
    ): Response {
        $tempFile = $tempFile->store($request->file);

        if ($importBookmarks->isEncrypted($request->file)) {
            return htmx()
                ->target('#dialog')
                ->swap('innerHTML')
                ->response(view('partials.bookmark.import-export.import-password', compact('tempFile')));
        }

        return htmx()
            ->target('#dialog')
            ->swap('innerHTML')
            ->response(view('partials.bookmark.import-export.import-confirm', compact('tempFile')));
    }
}
