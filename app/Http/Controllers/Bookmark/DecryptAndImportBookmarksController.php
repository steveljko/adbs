<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Actions\Bookmark\ImportBookmarksAction;
use App\Http\Requests\Bookmark\ImportBookmarksWithPasswordRequest;
use App\Support\TempFileManager;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * This controller is responsible for handling the decryption and import of bookmarks.
 * It checks for the existence of temporary file and processes the import with password.
 */
final class DecryptAndImportBookmarksController
{
    public function __invoke(
        ImportBookmarksWithPasswordRequest $request,
        TempFileManager $tempFile,
        ImportBookmarksAction $importBookmarks
    ): Response {
        $tempFileName = $request->get('temp_file');

        if (! $tempFileName || ! $tempFile->exists($tempFileName)) {
            return htmx()->toast(type: 'error', text: 'File not found.', altText: 'Please try uploading again.')->response();
        }

        try {
            $importBookmarks->execute(
                file: $tempFile->getPath($tempFileName),
                password: $request->get('password')
            );

            $tempFile->delete($tempFileName);

            return htmx()
                ->trigger('hideModal')
                ->toast(type: 'info', text: 'Import is starting!')
                ->target('#progressContainer')
                ->swap('innerHTML')
                ->response(view('partials.bookmark.import-export.import-progress'));
        } catch (DecryptException $e) {
            Log::warning('Invalid password provided for encrypted bookmark import', [
                'user_id' => Auth::id(),
                'temp_file' => $tempFileName,
            ]);

            $tempFile->delete($tempFileName);

            return htmx()->toast(type: 'error', text: 'Invalid password provided.', altText: 'Please try again.')->response();
        } catch (Exception $e) {
            Log::error('Encrypted bookmark import failed', [
                'error' => $e->getMessage(),
                'temp_file' => $tempFileName,
                'user_id' => Auth::id(),
            ]);

            $tempFile->delete($tempFileName);

            return htmx()->toast(type: 'error', text: 'Something went wrong.', altText: 'Please try again.')->response();
        }
    }
}
