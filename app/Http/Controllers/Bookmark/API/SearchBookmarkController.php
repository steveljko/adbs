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
        $request->validate(['url' => ['required', 'string']]);
        $url = urlencode($request->get('url'));

        $b = Bookmark::query()
            ->whereUserId(Auth::id())
            ->whereUrl($url)
            ->with(['tags' => function ($q) {
                $q->select('id', 'name');
            }])
            ->first();

        if ($b === null) {
            return new JsonResponse([
                'exists' => false,
                'bookmark' => [],
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'exists' => true,
            'bookmark' => $b,
        ], Response::HTTP_OK);
    }
}
