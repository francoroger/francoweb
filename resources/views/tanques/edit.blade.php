@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Editar Tanque</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('tanques.update', $tanque->id) }}" autocomplete="off">
        <input type="hidden" name="_method" value="PUT">
        @csrf
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="descricao">Descrição <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" placeholder="Descrição / Nome do Tanque" value="{{ old('descricao', $tanque->descricao) }}" required />
              @error('descricao')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-4">
              <label class="form-control-label" for="ciclo_reforco">Reforçar a cada</label>
              <div class="input-group">
                <input type="text" class="form-control @error('ciclo_reforco') is-invalid @enderror" id="ciclo_reforco" name="ciclo_reforco" value="{{ old('ciclo_reforco', $tanque->ciclo_reforco) }}" />
                <span class="input-group-addon">
                  gramas
                </span>
              </div>
              @error('ciclo_reforco')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('tanques.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>

    </div>
  </div>

@endsection
