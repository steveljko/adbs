<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark\API;

use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class SearchBookmarkController
{
    /**
     * Search bookmarks by bookmark URL.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['url' => ['required', 'string']);

        $url = urlencode($request->get('url'));

        $b = Bookmark::query()
            ->whereUserId(Auth::id())
            ->whereUrl($url)
            ->exists();

        $status = $b === true ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

        return new JsonResponse(['exists' => $b], $status);
    }
}
