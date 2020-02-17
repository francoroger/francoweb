@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/gauge-js/gauge.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/gauge-js/gauge.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/gauge.js') }}"></script>

  <script src="{{ asset('assets/examples/js/charts/gauges.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Controle de Reforço de Tanques</h1>
    </div>
    <div class="page-content container-fluid">

      <div class="row">
        @foreach ($tanques as $tanque)
          <div class="col-md-4">
            <div class="panel">
              <div class="panel-heading">
                <h3 class="panel-title">{{ $tanque->descricao }}</h3>
              </div>
              <div class="panel-body">
                <div class="gauge gauge-sm" data-plugin="gauge" data-value="{{ rand(10, $tanque->ciclo_reforco) }}" data-max-value="{{ $tanque->ciclo_reforco }}">
                  <div class="gauge-label"></div>
                  <canvas width="150" height="110"></canvas>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

@endsection
