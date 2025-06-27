<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Bookmark\CreateBookmarkController;
use App\Http\Controllers\Bookmark\DeleteBookmarkController;
use App\Http\Controllers\Bookmark\PreviewBookmarkController;
use App\Http\Controllers\Bookmark\UpdateBookmarkController;
use App\Http\Controllers\Dashboard\SearchBookmarksController;
use App\Http\Controllers\Dashboard\ShowDashboardController;
use App\Http\Controllers\Shared\GetAuthenticatedUserTagsController;
use App\Http\Controllers\Tags\DeleteTagController;
use App\Http\Controllers\Tags\EditTagController;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::group([
    'as' => 'auth',
    'prefix' => 'auth',
    'middleware' => 'guest',
], function () {
    Route::view('/login', 'resources.auth.login')->name('.login');
    Route::post('/login', LoginController::class)->name('.login.execute');
});

Route::view('/settings', 'resources.auth.settings')->middleware('auth')->name('auth.settings');
Route::delete('/logout', LogoutController::class)->middleware('auth')->name('auth.logout');

Route::group([
    'as' => 'bookmarks',
    'prefix' => 'bookmarks',
    'middleware' => 'auth',
], function () {
    Route::get('/create', [CreateBookmarkController::class, 'render'])->name('.create');
    Route::post('/store', CreateBookmarkController::class)->name('.store');
    Route::post('/preview', PreviewBookmarkController::class)->name('.preview');
    Route::get('/preview/tag', [PreviewBookmarkController::class, 'tagSuggest'])->name('.tagSuggest');

    Route::get('/{bookmark}/edit', [UpdateBookmarkController::class, 'render'])->name('.edit');
    Route::put('/{bookmark}/update', UpdateBookmarkController::class)->name('.update');

    Route::get('/{bookmark}/delete', [DeleteBookmarkController::class, 'render'])->name('.delete');
    Route::delete('/{bookmark}/delete', DeleteBookmarkController::class)->name('.destroy');
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

Route::group([
    'as' => 'tags',
    'prefix' => 'tags',
    'middleware' => 'auth',
], function () {
    Route::get('/', GetAuthenticatedUserTagsController::class);
    Route::get('/{tag}', [GetAuthenticatedUserTagsController::class, 'renderTag'])->name('.get');
    Route::get('/{tag}/edit', function (Tag $tag) {
        return view('resources.tags.edit', ['tag' => $tag]);
    })->name('.edit');
    Route::put('/{tag}/update', EditTagController::class)->name('.update');
    Route::delete('/{tag}', DeleteTagController::class)->name('.delete');
});
