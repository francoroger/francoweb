@extends('layouts.app.main')

@section('content')
  <div class="page">

    <div class="page-content">
      <div class="page-header">
        <h1 class="page-title">Catalogação #{{ $catalogacao->id }}</h1>
        <p class="page-description">{{ $catalogacao->cliente->nome ?? '' }}</p>
      </div>

      <div class="row">
        @foreach ($itens as $item)
          <div class="col-md-4">
            <div class="card border{{ $item->status_check == 'S' ? ' border-success' : '' }}{{ $item->status_check == 'N' ? ' border-danger' : '' }}">
              @if (file_exists('fotos/'.$item->foto))
                <img class="card-img-top w-full" src="{{ asset('fotos/'.$item->foto) }}" alt="{{ $item->foto }}">
              @else
                <img class="card-img-top w-full" src="{{ asset('assets/photos/placeholder.png') }}" alt="{{ $item->foto }}">
              @endif
              <div class="card-block">
                <h4 class="card-title">{!! $item->status_check == 'S' ? '<i class="fa fa-check text-success"></i>' : '' !!} {!! $item->status_check == 'N' ? '<i class="fa fa-close text-danger"></i>' : '' !!} {{ $item->produto->descricao ?? '' }}</h4>
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
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Bruto:</td>
                    <td class="p-10" style="width: 85%">R$ {{ number_format ($item->preco_bruto, 2, ',', '.') }}</td>
                  </tr>
                  <tr>
                    <td class="p-10 font-weight-500 text-right" style="width: 15%">Informação:</td>
                    <td class="p-10" style="width: 85%">{{ $item->observacoes }}</td>
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
