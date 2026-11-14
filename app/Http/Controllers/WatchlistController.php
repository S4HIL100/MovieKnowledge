<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WatchlistController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'media_id' => ['required', 'integer', 'min:1'],
            'type' => ['required', Rule::in(['movie', 'tv'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'poster_path' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->watchMedia()->create($validated);

        return back()->with('success', 'Added to watchlist!');
    }
}
