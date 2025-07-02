<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\RequirmentsController;
use App\Http\Controllers\Installer\UserCreationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
                Route::view('/', 'resources.installer.welcome')->name('welcome');

                Route::get('/requirments', RequirmentsController::class)->name('requirements');

                Route::get('/database', DatabaseController::class)->name('database');
                Route::post('/database/setup', [DatabaseController::class, 'run'])->name('database.setup');

                Route::get('/user', UserCreationController::class)->name('user');
                Route::post('/user/create', [UserCreationController::class, 'run'])->name('user.create');
            });
        }
    }
}
