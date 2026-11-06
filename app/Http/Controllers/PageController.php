<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function media(string $type, string $id): View
    {
        return view('media', [
            'type' => $type,
            'id' => $id,
        ]);
    }
}
