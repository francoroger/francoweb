@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>

@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Novo Recebimento</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('recebimentos.store') }}" autocomplete="off">
        @csrf
        <div class="panel-body">
          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="data_receb">Data de Entrada<span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('data_receb') is-invalid @enderror" id="data_receb" name="data_receb" value="{{ old('data_receb') }}" data-plugin="datepicker" data-language="pt-BR" required />
              @error('data_receb')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('recebimentos.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @endsection
