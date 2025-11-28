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
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                ->toast(type: 'info', text: 'Bookmark import has been started!')
                ->response();
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

    // TODO: improve this...
    public function progress()
    {
        $userId = Auth::id();

        return new StreamedResponse(function () use ($userId) {
            $lastProgress = -1;

            while (true) {
                $data = Cache::get("upload_progress_{$userId}");

                if (! $data) {
                    echo 'data: '.json_encode([
                        'code' => 'WAITING',
                        'message' => 'Job not found',
                    ])."\n\n";
                    ob_flush();
                    flush();
                    break;
                }

                if ($data['progress'] !== $lastProgress) {
                    echo 'data: '.json_encode($data)."\n\n";
                    ob_flush();
                    flush();
                    $lastProgress = $data['progress'];
                }

                if ($data['status'] === 'completed' || $data['status'] === 'failed') {
                    echo 'data: '.json_encode($data)."\n\n";
                    ob_flush();
                    flush();

                    // clean up cache after delay
                    Cache::forget("upload_progress_{$userId}");
                    break;
                }

                // avoid hammering the cache
                usleep(500000); // 0.5 seconds

                if (connection_aborted()) {
                    break;
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
