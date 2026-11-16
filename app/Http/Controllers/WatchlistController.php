<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WatchlistController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'media_id' => ['required', 'integer', 'min:1'],
            'type' => ['required', Rule::in(['movie', 'tv'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'poster_path' => ['nullable', 'string', 'max:255'],
        ]);

        $watchlist = $request->user()->watchMedia()->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $watchlist->getKey(),
                'isInWatchlist' => true,
                'message' => 'Added to watchlist.',
            ], 201);
        }

        return back()->with('success', 'Added to WatchList');
    }

    public function destroy(Request $request, Watchlist $watchlist): JsonResponse|RedirectResponse
    {
        $request->user()
            ->watchMedia()
            ->whereKey($watchlist->getKey())
            ->firstOrFail()
            ->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Removed from watchlist.']);
        }
    }
}
