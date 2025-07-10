<?php

declare(strict_types=1);

use App\Http\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Controllers\AddonClients\LoginAndGenerateTokenController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return new JsonResponse([
        'status' => 'ok',
    ], Response::HTTP_OK);
});

Route::post('/login', LoginAndGenerateTokenController::class);

Route::post('/bookmark', function (Request $request) {
    return app(CreateBookmarkAction::class)->execute(data: $request->all());
})->middleware(EnsureTokenIsValid::class);
