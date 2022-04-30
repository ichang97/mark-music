<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\oAuth\SpotifyAuthController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TrackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('oAuth')->name('oAuth.')->group(function(){
    Route::get('auth', [SpotifyAuthController::class, 'auth'])->name('auth');
    Route::get('redirect', [SpotifyAuthController::class, 'redirect'])->name('redirect');
    Route::get('refresh-token', [SpotifyAuthController::class, 'refreshToken'])->name('refresh-token');
});

Route::middleware('auth')->group(function(){
    Route::prefix('playlist')->name('playlist.')->group(function(){
        Route::get('/', [PlaylistController::class, 'getPlaylist'])->name('get');
        Route::get('{id}/tracks', [PlaylistController::class, 'getTracks'])->name('tracks');
    });

    Route::prefix('track')->name('track.')->group(function(){
        Route::get('{id}', [PlaylistController::class, 'track'])->name('get');
    });

    Route::prefix('player')->name('player.')->group(function(){
        Route::post('devices', [TrackController::class, 'availableDevices'])->name('devices');
        Route::post('play', [TrackController::class, 'play'])->name('play');
        Route::post('switch', [TrackController::class, 'switchPlayer'])->name('switch');
    });
    
});
