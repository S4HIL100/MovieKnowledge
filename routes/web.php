<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home']);

Route::get('/media/{type}/{id}', [PageController::class, 'media'])
    ->whereIn('type', ['movie', 'tv'])
    ->name('media');

Route::post('/watchlist', [WatchlistController::class, 'store'])
    ->middleware('auth');
