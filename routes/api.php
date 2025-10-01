<?php

declare(strict_types=1);

use App\Http\Controllers\Bookmark\API\StoreBookmarkController;
use App\Http\Controllers\Clients\Api\RefreshTokenController;
use App\Http\Controllers\Clients\Api\ShowTokenStatusController;
use App\Http\Controllers\Clients\LoginAndGenerateTokenController;
use App\Http\Middleware\EnsureRefreshTokenIsValidMiddleware;
use Illuminate\Http\JsonResponse;
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

Route::get('/token/status', ShowTokenStatusController::class)->middleware('auth:sanctum');
Route::post('/token/refresh', RefreshTokenController::class)->middleware(EnsureRefreshTokenIsValidMiddleware::class);

Route::group([
    'as' => 'bookmark',
    'prefix' => 'bookmark',
    'middleware' => 'auth:sanctum',
], function () {
    Route::post('/', StoreBookmarkController::class)->name('.store');
    Route::put('/{bookmark}/tags', StoreBookmarkController::class)->name('.attachTags');
});
