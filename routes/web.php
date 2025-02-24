<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth', 'prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::view('/login', 'resources.auth.login')->name('.login');
    Route::post('/login', LoginController::class)->name('.login');
});

Route::get('/dashboard', function () {
    return 'Dashboard';
})->middleware('auth')->name('dashboard');
