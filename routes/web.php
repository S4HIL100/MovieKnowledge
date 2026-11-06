<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home']);

Route::get('/media/{type}/{id}', [PageController::class, 'media'])
    ->whereIn('type', ['movie', 'tv'])
    ->name('media');
