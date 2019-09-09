@extends('layouts.auth.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/login-v3.css') }}">
@endpush

@section('content')
  <form method="POST" action="{{ route('login') }}">
    @csrf
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
      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
      <label class="floating-label">Senha</label>
      @error('password')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
      @enderror
    </div>
    <div class="form-group clearfix">
      <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg float-left">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember">Lembrar dados</label>
      </div>
      <!--<a class="float-right" href="{{ route('password.request') }}">Esqueceu a senha?</a>-->
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg mt-40">Login</button>
  </form>
  <!--<p>Não possui conta? <a href="{{ route('register') }}">Cadastre-se</a></p>-->
@endsection

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/jquery-placeholder.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/material.js') }}"></script>
@endpush
