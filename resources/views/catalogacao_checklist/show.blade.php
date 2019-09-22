@extends('layouts.app.main')

@section('content')
  <div class="page">

    <div class="page-content">
      <div class="page-header">
        <h1 class="page-title">Catalogação #{{ $catalogacao->id }}</h1>
        <p class="page-description">{{ $catalogacao->cliente->nome ?? '' }}</p>
      </div>

      <div class="row">
        @foreach ($catalogacao->itens->sortBy('produto.descricao') as $item)
          <div class="col-md-4">
            <div class="card{{ $item->check ? '' : ' border border-danger' }}">
              @if (file_exists('fotos/'.$item->foto))
                <img class="card-img-top w-full" src="{{ asset('fotos/'.$item->foto) }}" alt="{{ $item->foto }}">
              @else
                <img class="card-img-top w-full" src="{{ asset('assets/photos/placeholder.png') }}" alt="{{ $item->foto }}">
              @endif
              <div class="card-block">
                <h4 class="card-title">{!! $item->check ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-close text-danger"></i>' !!} {{ $item->produto->descricao ?? '' }}</h4>
                <table class="table table-bordered">
                  <tr>
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Material:</td>
                    <td class="p-10" style="width: 85%">{{ $item->material->descricao ?? '' }}</td>
                  </tr>
                  <tr>
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Fornecedor:</td>
                    <td class="p-10" style="width: 85%">{{ $item->fornecedor->nome ?? '' }}</td>
                  </tr>
                  <tr>
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Peso:</td>
                    <td class="p-10" style="width: 85%">{{ number_format ($item->peso, 2, ',', '.') }} g</td>
                  </tr>
                  <tr>
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Quantidade:</td>
                    <td class="p-10" style="width: 85%">{{ number_format ($item->quantidade, 0, ',', '.') }}</td>
                  </tr>
                  <tr>
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Obs:</td>
                    <td class="p-10" style="width: 85%">{{ $item->obs_check }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

  </div>
@endsection
