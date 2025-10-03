<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark\API;

use App\Actions\Bookmark\UpdateBookmarkAction;
use App\Http\Requests\Bookmark\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class UpdateBookmarkController
{
    public function __invoke(
        UpdateBookmarkRequest $request,
        UpdateBookmarkAction $updateBookmark,
        Bookmark $bookmark,
    ): JsonResponse {
        if (Auth::user()->cannot('update', $bookmark)) {
            return new JsonResponse(['message' => 'You are unathorized do perform this action.'], Response::HTTP_UNAUTHORIZED);
        }

        [$isChanged, $message] = array_values($updateBookmark->execute(
            bookmark: $bookmark,
            data: $request->validated()
        ));

        if (! $isChanged) {
            return new JsonResponse(['message' => 'Nothing is changed.'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $message], Response::HTTP_OK);
    }
}
