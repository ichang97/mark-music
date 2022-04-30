<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use Exception;
use Auth;

class TrackController extends Controller
{
    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.spotify.com/v1/'
        ]);
    }

    public function availableDevices(Request $request){
        if(Auth::user() && Auth::user()->spotify_access_token){
            $response = $this->client->get('me/player/devices', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Auth::user()->spotify_access_token
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            $devices_array = [
                'uri' => $request->uri,
                'devices' => $result['devices']
            ];

            $devices = collect($devices_array);

            if($response->getStatusCode() == 200){
                return view('devices', compact('devices'));
            }
        }
    }

    public function play(Request $request){
        if(Auth::user() && Auth::user()->spotify_access_token){
            $response = $this->client->put('me/player/play?device_id=' . $request->device, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Auth::user()->spotify_access_token
                ],
                'json' => [
                    'context_uri' => $request->uri
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() == 204){
                return response()->json(['status' => 'success', 'message' => 'played.'], 200);
            }else{
                return response()->json(['status' => 'error', 'message' => 'cannot play.'], 500);
            }
        }
    }

    public function switchPlayer(Request $request){
        if(Auth::user() && Auth::user()->spotify_access_token){
            $response = $this->client->put('me/player', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Auth::user()->spotify_access_token
                ],
                'json' => [
                    'device_ids' => [
                        $request->device
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() == 204){
                return response()->json(['status' => 'success', 'message' => 'switched.'], 200);
            }else{
                return response()->json(['status' => 'error', 'message' => 'cannot play.'], 500);
            }
        }
    }
}
