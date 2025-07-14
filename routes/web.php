<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Bookmark\CreateBookmarkController;
use App\Http\Controllers\Bookmark\DeleteBookmarkController;
use App\Http\Controllers\Bookmark\DestroyBookmarkController;
use App\Http\Controllers\Bookmark\EditBookmarkController;
use App\Http\Controllers\Bookmark\ExportBookmarksController;
use App\Http\Controllers\Bookmark\ImportBookmarksController;
use App\Http\Controllers\Bookmark\PreviewBookmarkController;
use App\Http\Controllers\Bookmark\StoreBookmarkController;
use App\Http\Controllers\Bookmark\UndoBookmarksImportController;
use App\Http\Controllers\Bookmark\UpdateBookmarkController;
use App\Http\Controllers\Clients\ActivateClientController;
use App\Http\Controllers\Clients\DeactivateClientController;
use App\Http\Controllers\Clients\DeleteClientController;
use App\Http\Controllers\Clients\DestroyClientController;
use App\Http\Controllers\Dashboard\SearchBookmarksController;
use App\Http\Controllers\Dashboard\ShowDashboardController;
use App\Http\Controllers\Shared\GetAuthenticatedUserTagsController;
use App\Http\Controllers\Tag\DeleteTagController;
use App\Http\Controllers\Tag\EditTagController;
use App\Http\Controllers\Tag\UpdateTagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::group([
    'as' => 'auth',
    'prefix' => 'auth',
    'middleware' => 'guest',
], function () {
    Route::view('/login', 'pages.auth.login')->name('.login');
    Route::post('/login', LoginController::class)->name('.login.execute');
});

Route::view('/settings', 'pages.auth.settings')->middleware('auth')->name('auth.settings');
Route::delete('/logout', LogoutController::class)->middleware('auth')->name('auth.logout');

Route::group([
    'as' => 'bookmarks',
    'prefix' => 'bookmarks',
    'middleware' => 'auth',
], function () {
    Route::get('/create', CreateBookmarkController::class)->name('.create');
    Route::post('/store', StoreBookmarkController::class)->name('.store');
    Route::post('/preview', PreviewBookmarkController::class)->name('.preview');
    Route::get('/preview/tag', [PreviewBookmarkController::class, 'tagSuggest'])->name('.tagSuggest');

    Route::get('/{bookmark}/edit', EditBookmarkController::class)->name('.edit');
    Route::put('/{bookmark}/update', UpdateBookmarkController::class)->name('.update');

    Route::get('/{bookmark}/delete', DeleteBookmarkController::class)->name('.delete');
    Route::delete('/{bookmark}/delete', DestroyBookmarkController::class)->name('.destroy');

    Route::get('/export', ExportBookmarksController::class)->name('.export');
    Route::post('/export/confirm', [ExportBookmarksController::class, 'confirm'])->name('.export.confirm');
    Route::get('/export/download', [ExportBookmarksController::class, 'get'])->name('.export.get');
    Route::post('/import', ImportBookmarksController::class)->name('.import');
    Route::post('/decryptAndImport', [ImportBookmarksController::class, 'decryptAndImport'])->name('.decryptAndImport');
    Route::get('/import/undo', UndoBookmarksImportController::class)->name('.import.undo');
    Route::delete('/import/undo/confirm', [UndoBookmarksImportController::class, 'confirm'])->name('.import.undo.confirm');
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
    Route::get('/{tag}/edit', EditTagController::class)->name('.edit');
    Route::put('/{tag}/update', UpdateTagController::class)->name('.update');
    Route::delete('/{tag}', DeleteTagController::class)->name('.delete');
});

Route::group([
    'as' => 'client',
    'prefix' => 'client',
    'middleware' => 'auth',
], function () {
    Route::get('/{addonClient}/delete', DeleteClientController::class)->name('.delete');
    Route::delete('/{addonClient}/destroy', DestroyClientController::class)->name('.destroy');
    Route::patch('/{addonClient}/activate', ActivateClientController::class)->name('.activate');
    Route::patch('/{addonClient}/deactivate', DeactivateClientController::class)->name('.deactivate');
});
