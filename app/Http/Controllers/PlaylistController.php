<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Auth;

class PlaylistController extends Controller
{
    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.spotify.com/v1/'
        ]);
    }

    public function getPlaylist(Request $request){
        $response = $this->client->get('me/playlists', [
            'headers' => [
                'Authorization' => 'Bearer ' . Auth::user()->spotify_access_token
            ]
        ]);

        $res_data = json_decode($response->getBody()->getContents(), true);

        $playlists = collect($res_data['items']);

        return view('playlist', compact('playlists'));
    }

    public function getTracks($id){
        $response = $this->client->get('playlists/' . $id . '/tracks', [
            'headers' => [
                'Authorization' => 'Bearer ' . Auth::user()->spotify_access_token
            ]
        ]);

        $res_data = json_decode($response->getBody()->getContents(), true);

        $tracks = collect($res_data['items']);

        return view('playlist_item', compact('tracks'));
    }

    public function track($id){
        $response = $this->client->get('tracks/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . Auth::user()->spotify_access_token
            ]
        ]);

        $res_data = json_decode($response->getBody()->getContents(), true);

        $track = collect($res_data);

        return view('view_track', compact('track'));
    }
}
