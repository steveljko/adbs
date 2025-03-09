<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bookmark\CreateBookmarkController;
use App\Http\Controllers\Bookmark\PreviewBookmarkController;
use App\Http\Controllers\Dashboard\SearchBookmarksController;
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
    Route::get('/', [CreateBookmarkController::class, 'render'])->name('.create');
    Route::post('/', CreateBookmarkController::class)->name('.store');
    Route::post('/preview', PreviewBookmarkController::class)->name('.preview');
});

Route::group([
    'as' => 'dashboard',
    'prefix' => 'dashboard',
    'middleware' => 'auth',
], function () {
    Route::get('/', ShowDashboardController::class);
    Route::post('/search', SearchBookmarksController::class)->name('.search');
    Route::get('/search/tag/{tag}', [SearchBookmarksController::class, 'renderTag'])->name('.search.tag');
    Route::get('/search/site/{site}', [SearchBookmarksController::class, 'renderSite'])->name('.search.site');
});
