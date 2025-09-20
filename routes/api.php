<?php

declare(strict_types=1);

use App\Enums\TokenStatus;
use App\Http\Actions\Bookmark\CreateBookmarkAction;
use App\Http\Controllers\Clients\LoginAndGenerateTokenController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return new JsonResponse([
        'status' => 'ok',
        'message' => 'Connection is okay',
        'data' => null,
    ], Response::HTTP_OK);
});

Route::post('/login', LoginAndGenerateTokenController::class);

Route::post('/bookmark', function (Request $request) {
    return app(CreateBookmarkAction::class)->execute(data: $request->all(), userId: $request->user->id);
})->middleware(EnsureTokenIsValid::class);

Route::get('/token/status', function (Request $request) {
    $status = $request->user()->currentAccessToken()->status;


    if ($status == TokenStatus::ACTIVE) {
        return new JsonResponse([
            'status' => $status->value,
        ], Response::HTTP_OK);
    } else {
        return new JsonResponse([
            'status' => $status->value,
        ], Response::HTTP_FORBIDDEN);
    }
})->middleware('auth:sanctum');
