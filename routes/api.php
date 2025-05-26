<?php

declare(strict_types=1);

use App\Http\Controllers\AddonClients\LoginAndGenerateTokenController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return new JsonResponse([
        'status' => 'ok',
    ], Response::HTTP_OK);
});

Route::post('/login', LoginAndGenerateTokenController::class);
