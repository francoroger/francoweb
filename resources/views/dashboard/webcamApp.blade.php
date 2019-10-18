@extends('layouts.app.main')

@push('stylesheets_plugins')
  <style media="screen">
  .card-block {
    padding: 9px 0;
  }

  .card-block .card-link + .card-link {
    margin-left: 0;
  }
  </style>
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/plugins/webcamjs/webcam.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/BaseApp.js') }}"></script>
  <script src="{{ asset('assets/js/App/Camera.js') }}"></script>

  <script src="{{ asset('assets/examples/js/apps/camera.js') }}"></script>
@endpush

@push('body-class', ' app-camera')

@section('content')
  <div class="page">
    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-4">

          <div class="card">
            <div class="image-wrap"></div>
            <img class="card-img-top img-fluid w-full preview-img" src="{{ asset('assets/photos/placeholder.png') }}" alt="...">
            <div class="card-block">
              <button type="button" class="btn btn-primary card-link enable-cam mr-5"><i class="icon wb-power"></i> Ligar</button>
              <button type="button" class="btn btn-primary card-link capture-img"><i class="icon wb-image"></i> Capturar</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
@endsection
