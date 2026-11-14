<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function media(Request $request, string $type, string $id): View
    {
        $user = $request->user();
        $isInWatchlist = $user !== null
            && $user->watchMedia()
                ->where('media_id', $id)
                ->where('type', $type)
                ->exists();

        return view('media', [
            'type' => $type,
            'id' => $id,
            'isInWatchlist' => $isInWatchlist,
        ]);
    }
}
