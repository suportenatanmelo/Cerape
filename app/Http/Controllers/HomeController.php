<?php

namespace App\Http\Controllers;

use App\Models\Home;

class HomeController extends Controller
{
    public function index()
    {
        $home = Home::query()->latest()->first();

        return view('frontend.layout', [
            'home' => $home,
        ]);
    }
}
