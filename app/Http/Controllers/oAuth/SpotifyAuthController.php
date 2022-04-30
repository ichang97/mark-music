<?php

namespace App\Http\Controllers\oAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use GuzzleHttp\Client;
use Auth;

class SpotifyAuthController extends Controller
{
    public function auth(Request $request){
        $scope = 'user-modify-playback-state user-read-playback-state user-read-currently-playing playlist-read-private app-remote-control streaming user-read-email user-read-private user-library-read';

        $endpoint = 'https://accounts.spotify.com/authorize?'
                    . 'response_type=code'
                    . '&client_id=' . config('app.spotify_client_id')
                    . '&redirect_uri=' . route('oAuth.redirect')
                    . '&scope=' . $scope; 

        return redirect($endpoint);
    }

    public function redirect(Request $request){
        $access_token = $request->get('code');

        if($access_token){
            $client = new Client([
                'base_uri' => 'https://accounts.spotify.com',
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . base64_encode(config('app.spotify_client_id') . ':' . config('app.spotify_client_secret'))
                ]
            ]);

            $response = $client->post('api/token',[
                'form_params' => [
                    'code' => $access_token,
                    'redirect_uri' => route('oAuth.redirect'),
                    'grant_type' => 'authorization_code'
                ]
            ]);

            $res_data = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() == 200 && $res_data['access_token']){
                $user = User::find(Auth::id());

                $user->spotify_access_token     = $res_data['access_token'];
                $user->spotify_refresh_token    = $res_data['refresh_token'];
                $user->update();

                return redirect(route('home'))->with('alert', ['status' => 'success', 'message' => 'spotify connected.']);
            }

            
        }else{
            return redirect(route('home'))->with('alert', ['status' => 'error', 'message' => 'access token required']);
        }
    }

    public function refreshToken(){
        $auth_user = Auth::user();

        if($auth_user && $auth_user->spotify_refresh_token){
            $client = new Client([
                'base_uri' => 'https://accounts.spotify.com',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode(config('app.spotify_client_id') . ':' . config('app.spotify_client_secret'))
                ]
            ]);

            $response = $client->post('api/token',[
                'form_params' => [
                    'refresh_token' => $auth_user->spotify_refresh_token,
                    'grant_type' => 'refresh_token'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() == 200 && $result['access_token']){
                // dd($result);

                $user                                   = User::find($auth_user->id);
                $user->spotify_access_token             = $result['access_token'];
                $user->update();
            }
        }
    }
}
