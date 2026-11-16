<?php

use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home']);

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::post('/register', Register::class)
    ->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::resource('/watchlist', WatchlistController::class)
        ->only(['store', 'destroy']);
});

Route::get('/media/{type}/{id}', [PageController::class, 'media'])
    ->whereIn('type', ['movie', 'tv'])
    ->name('media');
