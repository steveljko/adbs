<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bookmark\API;

use App\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Requests\Bookmark\CreateBookmarkRequest;

final class StoreBookmarkController
{
    public function __invoke(CreateBookmarkRequest $request)
    {
        return app(CreateBookmarkAction::class)->execute(data: $request->validated(), userId: auth('sanctum')->user()->id);
    }
}
