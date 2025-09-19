<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\RequirmentsController;
use App\Http\Controllers\Installer\UserCreationController;
use App\Http\Controllers\Installer\WelcomeController;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! file_exists(storage_path('installed'))) {
            Route::group([
                'prefix' => 'install',
                'as' => 'installer.',
            ], function () {
                Route::get('/', WelcomeController::class)->name('welcome');
                Route::post('/welcome/next', [WelcomeController::class, 'run'])->name('welcome.next');

                Route::get('/requirments', RequirmentsController::class)->name('requirements');

                Route::get('/database', DatabaseController::class)->name('database');
                Route::get('/database/select', [DatabaseController::class, 'select'])->name('database.select');
                Route::post('/database/setup', [DatabaseController::class, 'run'])->name('database.setup');

                Route::get('/user', UserCreationController::class)->name('user');
                Route::post('/user/create', [UserCreationController::class, 'run'])->name('user.create');
                Route::get('/user/skip', [UserCreationController::class, 'skip'])->name('user.skip');
            });
        }

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        View::composer([
            'pages.auth.settings',
            'partials.settings.tags',
            'partials.settings.clients',
        ], function ($view) {
            if (Auth::check()) {
                $view->with('tags', Auth::user()->tags()->orderBy('created_at', 'desc')->get());
                $view->with('clients', Auth::user()->tokens()->where('name', 'access_token')->with('info')->get());
            }
        });
    }
}
