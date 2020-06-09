@extends('layouts.app.main')

@section('content')
  <div class="page">
    <div class="page-header text-center">
      <h1 class="page-title font-size-26 font-weight-100">Atalhos</h1>
      <p class="page-description">
        Escolha uma opção abaixo ou no menu ao lado:
      </p>
    </div>
    <div class="page-content">
      <div class="row">
        @can('painel_acompanhamento.index')
        <div class="col-md-4">
          <a class="btn btn-info p-20 mb-10 w-full h-100 font-size-20" href="{{ route('painel') }}" role="button">
            <i class="icon wb-library" aria-hidden="true"></i> <br>
            Painel de Acompanhamento
          </a>
        </div>
        @endcan
        @can('checklist.index')
        <div class="col-md-4">
          <a class="btn bg-red-600 text-white p-20 mb-10 w-full h-100 font-size-20" href="{{ route('catalogacao_checklist.index') }}" role="button">
            <i class="icon wb-check-circle" aria-hidden="true"></i> <br>
            Check List
          </a>
        </div>
        @endcan
        @can('controle_reforco.index')
        <div class="col-md-4">
          <a class="btn btn-success p-20 mb-10 w-full h-100 font-size-20" href="{{ route('controle_reforco') }}" role="button">
            <i class="icon wb-pluse" aria-hidden="true"></i> <br>
            Controle de Reforço
          </a>
        </div>
        @endcan
      </div>
    </div>
  </div>
@endsection
