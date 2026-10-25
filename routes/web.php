<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/media/{type}/{id}', function (string $type, string $id) {
    return view('media', [
        'type' => $type,
        'id' => $id,
    ]);
})->whereIn('type', ['movie', 'tv'])->name('media');