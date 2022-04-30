@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        @foreach($tracks as $track)
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        {{$track['track']['name']}} - {{$track['track']['artists'][0]['name']}}<br />

                        <a href="{{ route('track.get', $track['track']['id']) }}" class="btn btn-warning">get track uri</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
