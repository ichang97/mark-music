<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\oAuth\SpotifyAuthController as Spotify;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $spotify        = new Spotify;
        $spotify->refreshToken();

        return view('home');
    }
}
