<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Bookmark\ExportBookmarksAction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class ExportBookmarksController
{
    public function __invoke(): View
    {
        return view('resources.bookmark.export');
    }

    public function confirm(Request $request, ExportBookmarksAction $exportBookmarks)
    {
        $data = $request->validate(['password' => 'nullable|min:6|max:255']);

        $data = $exportBookmarks->execute(for: Auth::user(), password: $data['password'] ?? null);
        $encodedData = json_encode($data);

        $token = Str::random(32);
        File::put(storage_path("app/temp/exported_$token"), $encodedData);

        return htmx()->redirect(route('bookmarks.export.get', ['token' => $token]))->response(null);
    }

    public function get(Request $request)
    {
        $token = $request->token;
        $filename = storage_path("app/temp/exported_$token");

        if (! File::exists($filename)) {
            abort(404, 'Export file not found.');
        }
        try {
            $file = File::get($filename);

            $r = response($file, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="bookmarks.json"',
            ]);

            File::delete($filename);

            return $r;

        } catch (Exception $e) {
            if (File::exists($filename)) {
                File::delete($filename);
            }
            abort(500, 'Error reading export file.');
        }
    }
}
