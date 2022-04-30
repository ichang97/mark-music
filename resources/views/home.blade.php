@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">SPOTIFY ACCOUNT</div>

                <div class="card-body">
                    @if(session()->get('alert'))
                        {{ json_encode(session()->get('alert'), true) }}
                    @endif

                    <a class="btn btn-success" href="{{ route('oAuth.auth') }}">Login to spotify</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">SPOTIFY SERVICE</div>

                <div class="card-body">
                    
                    <a class="btn btn-primary" href="{{ route('playlist.get') }}">get playlist</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
