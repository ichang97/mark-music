<div class="row">

    @foreach($devices['devices'] as $device)
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Device name : {{$device['name']}}</h6>

                    <button class="btn btn-success" data-action="play-track" data-uri="{{$devices['uri']}}" data-device="{{$device['id']}}">PLAY</button>
                    <button class="btn btn-success d-none" data-action="switch-player" data-device="{{$device['id']}}">SWITCH PLAYER</button>
                </div>
            </div>
        </div>
    @endforeach
</div>