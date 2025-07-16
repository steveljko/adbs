<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\ImportBookmarksAction;
use App\Support\TempFileManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * This controller handles the confirmation of bookmark imports.
 * It verifies the existence of temporary file and initiates the import process.
 */
final class ConfirmBookmarkImportController
{
    public function __invoke(
        Request $request,
        TempFileManager $tempFile,
        ImportBookmarksAction $importBookmarks
    ): Response {
        $tempFileName = $request->get('temp_file');

        if (! $tempFileName || ! $tempFile->exists($tempFileName)) {
            return htmx()->toast(type: 'error', text: 'File not found.', altText: 'Please try uploading again.')->response();
        }

        try {
            $importBookmarks->execute($tempFile->getPath($tempFileName));
            $tempFile->delete($tempFileName);

            return htmx()->toast(type: 'info', text: 'Import is starting!')->response();
        } catch (Exception $e) {
            Log::error('Bookmark import confirmation failed.', [
                'error' => $e->getMessage(),
                'temp_file' => $tempFileName,
                'user_id' => Auth::id(),
            ]);

            $tempFile->delete($tempFileName);

            return htmx()->toast('error', 'Import failed.', altText: 'Please try again!')->response();
        }
    }
}
