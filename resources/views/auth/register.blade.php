@extends('layouts.auth.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/register-v3.css') }}">
@endpush

@section('body-class', 'page-register-v3 layout-full')

@section('content')
  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group form-material floating" data-plugin="formMaterial">
      <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name">
      <label class="floating-label">Nome</label>
      @error('name')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
      @enderror
    </div>
    <div class="form-group form-material floating" data-plugin="formMaterial">
      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
      <label class="floating-label">E-mail</label>
      @error('email')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
      @enderror
    </div>
    <div class="form-group form-material floating" data-plugin="formMaterial">
      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
      <label class="floating-label">Senha</label>
      @error('password')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
      @enderror
    </div>
    <div class="form-group form-material floating" data-plugin="formMaterial">
      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
      <label class="floating-label">Confirmar Senha</label>
      @error('password')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg mt-40">Registrar</button>
  </form>
  <p>Ja possui conta? <a href="{{ route('login') }}">Entrar</a></p>
@endsection

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/jquery-placeholder.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/material.js') }}"></script>
@endpush
