@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/examples/css/webcam/camera.css') }}">

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
  <!-- Plugin webcamjs -->
  <script src="{{ asset('assets/plugins/webcamjs/webcam.js') }}"></script>
@endpush

@push('scripts_page')
  <!-- Plugin Camera -->
  <script src="{{ asset('assets/js/Plugin/camera.js') }}"></script>
  <!-- Exemplo Camera -->
  <script src="{{ asset('assets/examples/js/webcam/camera.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-6">

          <div class="panel" id="exampleCamera1">
            <div class="panel-heading">
              <h3 class="panel-title">Capturar Imagem</h3>
              <div class="panel-actions">
                <button type="button" class="panel-action btn btn-default btn-outline" data-action="camera-toggle-enable">
                  <i class="fa fa-power-off mx-5 text"></i>
                  <i class="fa fa-power-off mx-5 text-active text-danger"></i>
                </button>
                <button type="button" class="panel-action btn btn-default btn-outline" data-action="camera-freeze">
                  <i class="fa fa-camera mx-5"></i>
                </button>
                <button type="button" class="panel-action btn btn-default btn-outline" data-action="camera-snapshot">
                  <i class="fa fa-check mx-5"></i>
                </button>
              </div>
            </div>
            <div class="panel-body">
              <div class="live-cam"></div>
              <figure class="overlay">
                <img class="card-img-top img-fluid w-full overlay-figure preview-img" src="{{ asset('assets/photos/placeholder.png') }}" alt="Preview" />
                <div class="overlay-panel overlay-icon overlay-background">
                  <i class="icon wb-image"></i>
                </div>
              </figure>
            </div>
          </div>

        </div>

        
      </div>

    </div>
  </div>
@endsection
