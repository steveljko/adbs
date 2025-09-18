<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Bookmark\ConfirmBookmarkImportController;
use App\Http\Controllers\Bookmark\CreateBookmarkController;
use App\Http\Controllers\Bookmark\DecryptAndImportBookmarksController;
use App\Http\Controllers\Bookmark\DeleteBookmarkController;
use App\Http\Controllers\Bookmark\DestroyBookmarkController;
use App\Http\Controllers\Bookmark\EditBookmarkController;
use App\Http\Controllers\Bookmark\ExportBookmarksController;
use App\Http\Controllers\Bookmark\PreviewBookmarkController;
use App\Http\Controllers\Bookmark\RequestBookmarkImportController;
use App\Http\Controllers\Bookmark\StoreBookmarkController;
use App\Http\Controllers\Bookmark\UndoBookmarksImportController;
use App\Http\Controllers\Bookmark\UpdateBookmarkController;
use App\Http\Controllers\Clients\ActivateClientController;
use App\Http\Controllers\Clients\DeactivateClientController;
use App\Http\Controllers\Clients\DeleteClientController;
use App\Http\Controllers\Clients\DestroyClientController;
use App\Http\Controllers\Clients\ShowClientController;
use App\Http\Controllers\Dashboard\SearchBookmarksController;
use App\Http\Controllers\Dashboard\ShowDashboardController;
use App\Http\Controllers\Settings\ChangeViewTypeController;
use App\Http\Controllers\Settings\DisableViewSwitchController;
use App\Http\Controllers\Shared\GetAuthenticatedUserTagsController;
use App\Http\Controllers\Tag\DeleteTagController;
use App\Http\Controllers\Tag\EditTagController;
use App\Http\Controllers\Tag\UpdateTagController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\Request;
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

Route::delete('/logout', LogoutController::class)->middleware('auth')->name('auth.logout');

Route::group([
    'as' => 'settings',
    'prefix' => 'settings',
    'middleware' => 'auth',
], function () {
    Route::view('/', 'pages.auth.settings');

    Route::put('/viewType', ChangeViewTypeController::class)->name('.viewType');
    Route::put('/disableViewSwitch', DisableViewSwitchController::class)->name('.disableViewSwitch');
});

Route::group([
    'as' => 'bookmarks',
    'prefix' => 'bookmarks',
    'middleware' => 'auth',
], function () {
    Route::get('/create', CreateBookmarkController::class)->name('.create');
    Route::post('/store', StoreBookmarkController::class)->name('.store');
    Route::post('/preview', PreviewBookmarkController::class)->name('.preview');
    Route::get('/preview/tag', [PreviewBookmarkController::class, 'tagSuggest'])->name('.tagSuggest');

    Route::get('/{bookmark}/edit', EditBookmarkController::class)
        ->name('.edit')
        ->middleware('can:update,bookmark');
    Route::put('/{bookmark}/update', UpdateBookmarkController::class)->name('.update');

    Route::get('/{bookmark}/delete', DeleteBookmarkController::class)->name('.delete')->middleware('can:delete,bookmark');
    Route::delete('/{bookmark}/destroy', DestroyBookmarkController::class)->name('.destroy');

    Route::group([
        'as' => '.export',
        'prefix' => 'export',
    ], function () {
        Route::get('/', ExportBookmarksController::class);
        Route::post('/confirm', [ExportBookmarksController::class, 'confirm'])->name('.confirm');
        Route::get('/download', [ExportBookmarksController::class, 'get'])->name('.get');
    });

    Route::group([
        'as' => '.import',
        'prefix' => 'import',
    ], function () {
        Route::post('/', RequestBookmarkImportController::class);
        Route::post('/confirm', ConfirmBookmarkImportController::class)->name('.confirm');
        Route::post('/decrypt', DecryptAndImportBookmarksController::class)->name('.decrypt');
        Route::get('/undo', UndoBookmarksImportController::class)->name('.undo');
        Route::delete('/undo/confirm', [UndoBookmarksImportController::class, 'confirm'])->name('.undo.confirm');
    });
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
    Route::get('/{tag}/edit', EditTagController::class)->name('.edit')->middleware('can:update,tag');
    Route::put('/{tag}/update', UpdateTagController::class)->name('.update');
    Route::delete('/{tag}', DeleteTagController::class)->name('.delete');
});

Route::group([
    'as' => 'client',
    'prefix' => 'client',
    'middleware' => 'auth',
], function () {
    Route::get('/{addonClient}', ShowClientController::class)->name('.show');
    Route::get('/{addonClient}/delete', DeleteClientController::class)->name('.delete');
    Route::delete('/{addonClient}/destroy', DestroyClientController::class)->name('.destroy');
    Route::patch('/{addonClient}/activate', ActivateClientController::class)->name('.activate');
    Route::patch('/{addonClient}/deactivate', DeactivateClientController::class)->name('.deactivate');
});
