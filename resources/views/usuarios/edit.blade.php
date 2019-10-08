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
      <h1 class="page-title font-size-26 font-weight-100">Editar Usuário</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('usuarios.update', $usuario->id) }}" autocomplete="off">
        <input type="hidden" name="_method" value="PUT">
        @csrf
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="name">Nome <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nome" value="{{ old('name', $usuario->name) }}" required />
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="email">E-mail <span class="text-danger">*</span></label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail" value="{{ old('email', $usuario->email) }}" required />
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label class="form-control-label" for="password">Senha <span class="text-danger">*</span></label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Senha" required />
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label class="form-control-label" for="password-confirm">Confirmar Senha <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Digite a senha novamente" required />
            </div>
          </div>

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('usuarios.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>


    </div>
  </div>

@endsection
