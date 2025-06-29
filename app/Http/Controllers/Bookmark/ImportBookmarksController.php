<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark;

use App\Http\Actions\Tag\AttachOrCreateTagsAction;
use App\Http\Actions\Website\GetFaviconAction;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ImportBookmarksController
{
    public function __invoke(Request $request, AttachOrCreateTagsAction $action, GetFaviconAction $getFavicon)
    {
        $file = $request->file;

        $content = file_get_contents($file->getRealPath());

        $bookmarks = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON file'], 400);
        }

        foreach ($bookmarks as $bookmark) {
            $toInsert = [
                'url' => $bookmark['url'],
                'title' => $bookmark['title'],
                'favicon' => $getFavicon->execute($bookmark['url'], 32),
                'status' => $bookmark['status'],
                'user_id' => Auth::id(),
            ];

            $b = Bookmark::create($toInsert);

            if (isset($bookmark['tags'])) {
                $action->execute(bookmark: $b, tags: $bookmark['tags']);
            }
        }

        return htmx()->toast(type: 'success', text: 'Successfully imported bookmarks!')->response(null);
    }
}
