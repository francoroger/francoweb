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
      <h1 class="page-title font-size-26 font-weight-100">Cadastrar Material</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('materiais.store') }}" autocomplete="off">
        @csrf
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="descricao">Descrição <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" placeholder="Descrição / Nome do Material" value="{{ old('descricao') }}" required />
              @error('descricao')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="cores">Cores</label>
              <select id="cores" name="cores[]" class="form-control" multiple data-plugin="select2">
                @foreach ($cores as $cor)
                  <option value="{{ $cor->id }}"{{ in_array($cor->id, old("cores",[])) ? ' selected' : '' }}>{{ $cor->descricao }}</option>
                @endforeach
              </select>
              @error('cores')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('materiais.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>

    </div>
  </div>

@endsection
