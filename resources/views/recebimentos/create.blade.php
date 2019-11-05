@extends('layouts.app.main')

@section('content')
  <div class="page">
    <form class="panel" method="post" action="{{ route('recebimentos.store') }}" autocomplete="off">
      @csrf
      <div class="page-header">
        <h1 class="page-title font-size-26 font-weight-100">Novo Recebimento</h1>
      </div>

      <div class="page-content">
        <div class="panel-body container-fluid">

          

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('recebimentos.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
