<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\ImportBookmarksAction;
use App\Http\Requests\Bookmark\ImportBookmarksRequest;
use App\Http\Requests\Bookmark\ImportBookmarksWithPasswordRequest;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// TODO: add proper logging
final class ImportBookmarksController
{
    public function __invoke(ImportBookmarksRequest $request, ImportBookmarksAction $importBookmarks)
    {
        if ($importBookmarks->isEncrypted($request->file)) {
            $tempFileName = 'bookmarks_'.Str::uuid().'.json';
            $tempPath = 'temp/'.$tempFileName;

            Storage::putFileAs('temp', $request->file, $tempFileName);

            return htmx()
                ->target('#dialog')
                ->swap('innerHTML')
                ->response(view('resources.bookmark.import-password', ['tempFile' => $tempFileName]));
        }

        try {
            $importBookmarks->execute($request->file);

            return htmx()->toast(type: 'info', text: 'Import is starting!')->response();
        } catch (Exception $e) {
            return htmx()->toast(type: 'error', text: 'Import failed')->response();
        }
    }

    public function decryptAndImport(
        ImportBookmarksWithPasswordRequest $request,
        ImportBookmarksAction $importBookmarks
    ) {
        $tempFilePath = 'temp/'.$request->get('temp_file', null);

        if (! Storage::exists($tempFilePath)) {
            return htmx()->toast('error', 'Temporary file not found. Please try uploading again.')->response();
        }

        try {
            $fullPath = Storage::path($tempFilePath);
            $imported = $importBookmarks->execute($fullPath, $request->get('password'));

            Storage::delete($tempFilePath);

            return htmx()->toast(type: 'info', text: 'Import is stating')->response();
        } catch (DecryptException $e) {
            Storage::delete($tempFilePath);

            return htmx()->toast(type: 'error', text: 'Invalid password is provided, try again.')->response();
        } catch (Exception $e) {
            Storage::delete($tempFilePath);

            return htmx()->toast(type: 'error', text: 'Something wrong happend, try again.')->response();
        }
    }
}
