<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bookmark\CreateBookmarkController;
use App\Http\Controllers\Bookmark\PreviewBookmarkController;
use App\Http\Controllers\Dashboard\ShowDashboardController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'auth',
    'prefix' => 'auth',
    'middleware' => 'guest',
], function () {
    Route::view('/login', 'resources.auth.login')->name('.login');
    Route::post('/login', LoginController::class)->name('.login');
});

Route::group([
    'as' => 'bookmarks',
    'prefix' => 'bookmarks',
    'middleware' => 'auth',
], function () {
    // Create
    Route::get('/', [CreateBookmarkController::class, 'render'])->name('.create');
    Route::post('/', CreateBookmarkController::class)->name('.store');
    Route::post('/preview', PreviewBookmarkController::class)->name('.preview');
});

Route::get('/dashboard', ShowDashboardController::class)
    ->middleware('auth')
    ->name('dashboard');
