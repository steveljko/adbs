<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\ImportBookmarksAction;
use App\Http\Requests\Bookmark\ImportBookmarksRequest;
use App\Http\Requests\Bookmark\ImportBookmarksWithPasswordRequest;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// TODO: add proper logging
final class ImportBookmarksController
{
    public function __invoke(ImportBookmarksRequest $request, ImportBookmarksAction $importBookmarks)
    {
        if ($importBookmarks->isEncrypted($request->file)) {
            $tempFileName = 'bookmarks_'.Str::uuid().'.json';
            Storage::putFileAs('temp', $request->file, $tempFileName);

            return htmx()
                ->target('#dialog')
                ->swap('innerHTML')
                ->response(view('partials.bookmark.import-export.import-password', ['tempFile' => $tempFileName]));
        }

        $tempFileName = 'bookmarks_'.Str::uuid().'.json';
        Storage::putFileAs('temp', $request->file, $tempFileName);

        return htmx()
            ->target('#dialog')
            ->swap('innerHTML')
            ->response(view('partials.bookmark.import-export.import-confirm', ['tempFile' => $tempFileName]));
    }

    public function confirm(Request $request, ImportBookmarksAction $importBookmarks): Response
    {
        $tempFilePath = 'temp/'.$request->get('temp_file', null);

        try {
            $fullPath = Storage::path($tempFilePath);
            $importBookmarks->execute($fullPath);

            return htmx()->toast(type: 'info', text: 'Import is starting!')->response();
        } catch (Exception $e) {
            dd($e);

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
            $importBookmarks->execute($fullPath, $request->get('password'));

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
