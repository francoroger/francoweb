@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/modules/css/catalogacao_checklist/checklist.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.css') }}">
  <style media="screen">
    .checkbox-right {
      position: absolute;
      right: 0 !important;
      margin-right: 5px !important;
    }
  </style>
@endpush

@push('scripts_plugin')
  <script src="{{ asset('assets/vendor/bootbox/bootbox.js') }}"></script>

  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/sticky-header.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/asscrollable.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/action-btn.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/asselectable.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/selectable.js') }}"></script>
  <script src="{{ asset('assets/js/BaseApp.js') }}"></script>
  <script src="{{ asset('assets/js/App/CatalogacaoChecklist.js') }}"></script>

  <script src="{{ asset('assets/examples/js/apps/catalogacao_checklist.js') }}"></script>

  <script src="{{ asset('assets/js/Plugin/bootbox.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/toastr.js') }}"></script>

  <script src="{{ asset('assets/examples/js/advanced/bootbox-sweetalert.js') }}"></script>
@endpush

@section('body-class', 'app-media')

@section('content')
  <div class="page">

    <!-- Media Content -->
    <div class="page-main">
      <form action="{{ route('catalogacao_checklist.update', $catalogacao->id) }}" method="POST" autocomplete="off">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $catalogacao->id }}">
        {{ csrf_field() }}

        <!-- Media Content Header -->
        <div class="page-header">
          <h1 class="page-title">Catalogação #{{ $catalogacao->id }}</h1>
          <p class="page-description">{{ $catalogacao->cliente->nome ?? '' }}</p>
          <div class="page-header-actions">
            <div class="float-right">
              <button type="submit" class="btn btn-success btn-sm send"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
              <div class="btn-group media-arrangement" role="group">
                <button class="btn btn-outline btn-default active" id="arrangement-grid" type="button" data-toggle="tooltip" data-placement="top" title="Exibir em grade"><i class="icon wb-grid-4" aria-hidden="true"></i></button>
                <button class="btn btn-outline btn-default" id="arrangement-list" type="button" data-toggle="tooltip" data-placement="top" title="Exibir em lista"><i class="icon wb-list" aria-hidden="true"></i></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Media Content -->
        <div id="mediaContent" class="page-content page-content-table" data-plugin="selectable">
          <!-- Media -->
          <div class="media-list is-grid pb-50" data-child="li">
            <ul class="blocks blocks-100 blocks-xxl-4 blocks-xl-3 blocks-lg-3 blocks-md-2 blocks-sm-2" data-child=">li">
              @foreach ($itens as $i => $item)
                <li>
                  <input type="hidden" name="itens[{{$i}}][id]" value="{{ $item->id }}">
                  <div class="media-item bg-white">
                    <div class="checkbox-custom checkbox-success checkbox-lg">
                      <input type="hidden" name="itens[{{$i}}][check]" value="false">
                      <input type="checkbox" name="itens[{{$i}}][check]" value="true" class="selectable-item" id="media_{{$i}}" {{ $item->check ? 'checked' : '' }} />
                      <label for="media_{{$i}}"></label>
                    </div>
                    <div class="image-wrap">
                      @if (file_exists('fotos/'.$item->foto))
                        <img class="image img-rounded" src="{{ asset('fotos/'.$item->foto) }}" alt="{{ $item->foto }}">
                      @else
                        <img class="image img-rounded" src="{{ asset('assets/photos/placeholder.png') }}" alt="{{ $item->foto }}">
                      @endif
                    </div>
                    <div class="info-wrap">
                      <div class="title text-center"><h4>{{ $item->produto->descricao ?? '' }}</h4></div>
                      <table class="table table-bordered">
                        <tr>
                          <td class="p-10 font-weight-500 text-right" style="width: 15%">Material:</td>
                          <td class="p-10" style="width: 85%">{{ $item->material->descricao ?? '' }} {{ $item->milesimos ? "$item->milesimos mil" : '' }}</td>
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
                          <td class="p-10 font-weight-500 text-right" style="width: 15%">Informação:</td>
                          <td class="p-10" style="width: 85%">{{ $item->observacoes }}</td>
                        </tr>
                        <tr>
                          <td class="p-10 font-weight-500 text-right" style="width: 15%; vertical-align: middle;">Obs:</td>
                          <td class="p-10" style="width: 85%">
                            <div class="form-group form-material mb-0">
                              <input type="text" class="form-control" name="itens[{{$i}}][obs_check]" value="{{ $item->obs_check }}" />
                            </div>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="page-content">
          <div class="row">
            <div class="form-group col-md-12 text-right">
              <a class="btn btn-default btn-lg btn-squared" href="{{ route('catalogacao_checklist.index') }}" role="button">Cancelar</a>
              <button type="submit" class="btn btn-success btn-lg btn-squared send"><i class="fa fa-check" aria-hidden="true"></i> Confirmar</button>
            </div>
          </div>
        </div>


      </form>

    </div>
  </div>

@endsection
