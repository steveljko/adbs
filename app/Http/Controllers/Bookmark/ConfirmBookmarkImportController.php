<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Actions\Bookmark\ImportBookmarksAction;
use App\Support\TempFileManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
    ) {
        $tempFileName = $request->get('temp_file');

        if (! $tempFileName || ! $tempFile->exists($tempFileName)) {
            return htmx()->toast(type: 'error', text: 'File not found.', altText: 'Please try uploading again.')->response();
        }

        try {
            $importBookmarks->execute($tempFile->getPath($tempFileName));
            $tempFile->delete($tempFileName);

            return htmx()
                ->trigger('hideModal')
                ->toast(type: 'info', text: 'Import is starting!')
                ->target('#progressContainer')
                ->swap('innerHTML')
                ->response(view('partials.bookmark.import-export.import-progress'));
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

    public function progress()
    {
        $userId = Auth::id();
        $data = Cache::get("upload_progress_{$userId}");

        if (! $data) {
            $data = [
                'progress' => 0,
                'status' => 'waiting',
                'message' => 'No upload in progress',
            ];
        }

        $isComplete = in_array($data['status'], ['completed', 'failed']);

        // Stop polling when complete
        if ($isComplete) {
            return htmx()
                ->trigger('progressComplete')
                ->target('#progress')
                ->swap('innerHTML')
                ->response(view('partials.bookmark.import-export.import-progress', [
                    'progress' => $data['progress'],
                    'message' => $data['message'],
                ])->fragment('content'));
            Cache::forget("upload_progress_{$userId}");
        }

        return htmx()
            ->target('#progress')
            ->swap('innerHTML')
            ->response(view('partials.bookmark.import-export.import-progress', [
                'progress' => $data['progress'],
                'message' => $data['message'],
            ])->fragment('content'));
    }
}
