@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    SPOTIFY TRACK URI : {{ $track['uri'] }}<br />

                    <button class="btn btn-success" data-action="get-device" data-uri="{{$track['uri']}}">SELECT DEVICES</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="devices-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Devices</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="devices-result">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

</div>
@endsection

@push('script')
    <script>
        $(document).on('click', '[data-action="get-device"]', function(){
            let uri = $(this).data('uri')

            $.ajax({
                type: 'POST',
                url: '{{ route("player.devices") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    uri: uri
                },
                success: function(result){
                    $('#devices-result').html(result)
                }
            })

            var modal = new bootstrap.Modal(document.getElementById('devices-modal'))
            modal.show()
        })

        $(document).on('click', '[data-action="play-track"]', function(){
            let uri = $(this).data('uri')
            let device = $(this).data('device')

            $.ajax({
                type: 'POST',
                url: '{{ route("player.play") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    uri: uri,
                    device: device
                },
                success: function(result){
                    console.log(result)
                },
                error: function(res){
                    let error = res.responseJSON
                    console.log(error)
                }
            })

        })
    </script>
@endpush