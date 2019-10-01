@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/modules/css/catalogacao_checklist/checklist.css') }}">
@endpush

@push('scripts_plugins')

@endpush

@push('scripts_page')
  <script src="{{ asset('assets/modules/js/catalogacao_checklist/check.js') }}"></script>
@endpush

@section('body-class', 'app-media')

@section('content')
  <div class="page">
    <form action="{{ route('catalogacao_checklist.update', $catalogacao->id) }}" method="POST" autocomplete="off">

      <div class="page-header">
        <h1 class="page-title">Catalogação #{{ $catalogacao->id }}</h1>
        <p class="page-description">{{ $catalogacao->cliente->nome ?? '' }}</p>
        <!-- Ações -->
        <div class="page-header-actions">
          <div class="float-right">
            <button type="submit" class="btn btn-success btn-sm send"><i class="icon wb-check" aria-hidden="true"></i> Salvar</button>
            <div class="btn-group media-arrangement" role="group">
              <button class="btn btn-outline btn-default active" id="arrangement-grid" type="button" data-toggle="tooltip" data-placement="top" title="Exibir em grade"><i class="icon wb-grid-4" aria-hidden="true"></i></button>
              <button class="btn btn-outline btn-default" id="arrangement-list" type="button" data-toggle="tooltip" data-placement="top" title="Exibir em lista"><i class="icon wb-list" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>

        <!-- Fltros -->
        <div class="row">
          <div class="col-md-12">
            <span class="font-weight-500 mr-10">Filtros: </span>
            <div class="inline-block dropdown">
              <span id="projects-menu" data-toggle="dropdown" aria-expanded="false" role="button">
                Todos os Produtos
                <i class="icon wb-chevron-down-mini" aria-hidden="true"></i>
              </span>
              <div class="dropdown-menu animation-scale-up animation-top-left animation-duration-250"
                aria-labelledby="projects-menu" role="menu">
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1">Sort One</a>
                <a class="active dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1">Sort Two</a>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1">Sort Three</a>
              </div>
            </div>
            <div class="inline-block mx-5"></div>
            <div class="inline-block dropdown">
              <span id="projects-menu" data-toggle="dropdown" aria-expanded="false" role="button">
                Todos os Materiais
                <i class="icon wb-chevron-down-mini" aria-hidden="true"></i>
              </span>
              <div class="dropdown-menu animation-scale-up animation-top-left animation-duration-250"
                aria-labelledby="projects-menu" role="menu">
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1">Sort One</a>
                <a class="active dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1">Sort Two</a>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1">Sort Three</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="mediaContent" class="page-content page-content-table">
        <div class="media-list is-grid" data-child="li">
          <ul class="blocks blocks-100 blocks-xxl-4 blocks-lg-3 blocks-md-2">
            @foreach ($itens as $item)
              <li>
                <div class="media-item bg-white">
                  <div class="checkbox-custom checkbox-success checkbox-lg checkbox-custom-left">
                    <input type="radio" name="itens[{{$loop->index}}][check]" value="true" id="media_{{$loop->index}}" {{ $item->check == 1 ? 'checked' : '' }} />
                    <label for="media_{{$loop->index}}"></label>
                  </div>

                  <div class="checkbox-custom checkbox-danger checkbox-lg checkbox-custom-right">
                    <input type="radio" name="itens[{{$loop->index}}][check]" value="false" id="media_u{{$loop->index}}" {{ $item->check == 2 ? 'checked' : '' }} />
                    <label for="media_u{{$loop->index}}"></label>
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
                            <input type="text" class="form-control" name="itens[{{$loop->index}}][obs_check]" value="{{ $item->obs_check }}" />
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

      <div class="container-fluid">
        <!-- Salvar/Cancelar -->
        <div class="row">
          <div class="col-md-12 text-right">
            <div class="form-group">
              <a href="{{ route('catalogacao_checklist.index') }}" class="btn btn-default">Cancelar</a>
              <button type="button" class="btn btn-success"><i class="icon wb-check"></i> Salvar</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
