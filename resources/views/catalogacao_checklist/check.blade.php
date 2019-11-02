@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/modules/css/catalogacao_checklist/check.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/ladda/ladda.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/magnific-popup/magnific-popup.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/vendor/isotope/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ladda/spin.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ladda/ladda.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/modules/js/catalogacao_checklist/check.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/filterable.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/ladda.js') }}"></script>
@endpush

@section('body-class', 'app-media')

@section('content')
  <div class="page">
    <form id="check-form" action="{{ route('catalogacao_checklist.update', $catalogacao->id) }}" method="POST" autocomplete="off">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="id" value="{{ $catalogacao->id }}">
      <input type="hidden" id="status" name="status" value="C">
      {{ csrf_field() }}

      <div class="page-header">
        <div class="row">
          <div class="col-md-9">
            <h1 class="page-title">Catalogação #{{ $catalogacao->id }}</h1>
            <p class="page-description">{{ $catalogacao->cliente->nome ?? '' }}</p>
          </div>
          <div class="col-md-3 text-right">
            <!-- Ações -->
            <button type="button" id="btn-autosave" class="btn btn-default btn-sm ladda-button" data-url="{{ route('catalogacao_checklist.autosave') }}" data-token="{{ csrf_token() }}" data-style="expand-left" data-size="xs" disabled>
              <span class="ladda-label">
                <i class="icon wb-check mr-10" aria-hidden="true"></i> Salvo
              </span>
            </button>
          </div>
        </div>

        <!-- Fltros -->
        <div class="row">
          <div class="col-md-12">
            <span class="font-weight-500 mr-10">Filtros: </span>
            <div class="fdropdown dropdown">
              <button class="btn btn-block btn-secondary dropdown-toggle selected-item" type="button" id="produtos-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Todos os Produtos
              </button>
              <div class="dropdown-menu w-full animation-scale-up animation-top-left animation-duration-250 exampleFilter" aria-labelledby="produtos-menu" role="menu">
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="*">Todos os Produtos</a>
                <div class="dropdown-divider"></div>
                @foreach ($produtos as $i => $produto)
                  <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="Produto_{{ $i }}">{{ $produto }}</a>
                @endforeach
              </div>
            </div>
            <div class="fspacer mx-5 my-5"></div>
            <div class="fdropdown dropdown">
              <button class="btn btn-block btn-secondary dropdown-toggle selected-item" type="button" id="materiais-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Todos os Materiais
              </button>
              <div class="dropdown-menu w-full animation-scale-up animation-top-left animation-duration-250 exampleFilter" aria-labelledby="materiais-menu" role="menu">
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="*">Todos os Materiais</a>
                <div class="dropdown-divider"></div>
                @foreach ($materiais as $i => $material)
                  <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="Material_{{ $i }}">{{ $material }}</a>
                @endforeach
              </div>
            </div>
            <div class="fspacer mx-5 my-5"></div>
            <div class="fdropdown dropdown">
              <button class="btn btn-block btn-secondary dropdown-toggle selected-item" type="button" id="status-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Todas as Situações
              </button>
              <div class="dropdown-menu w-full animation-scale-up animation-top-left animation-duration-250 exampleFilter" aria-labelledby="status-menu" role="menu">
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="*">Todas as Situações</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="Status_Verificado">Verificados</a>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="Status_Aprovado">Aprovados</a>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="Status_Reprovado">Reprovados</a>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="Status_NaoVerificado">Não Verificados</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="mediaContent" class="page-content page-content-table">
        <div class="media-list is-grid" data-child="li">
          <ul class="blocks blocks-100 blocks-xxl-4 blocks-lg-3 blocks-md-2" id="itens_catalogo">
            @foreach ($itens as $item)
              <li class="{{ $item->idmaterial ? 'Material_'.$item->idmaterial  : '' }} {{ $item->idproduto ? 'Produto_'.$item->idproduto  : '' }} {{ $item->status_check ? ($item->status_check == 'S' ? 'Status_Verificado Status_Aprovado' : 'Status_Verificado Status_Reprovado') : 'Status_NaoVerificado' }}">
                <input type="hidden" name="itens[{{$loop->index}}][id]" value="{{ $item->id }}">
                <div class="media-item bg-white{{ $item->status_check == 'S' ? ' bg-green-100' : '' }}{{ $item->status_check == 'N' ? ' bg-red-100' : '' }}">
                  <div class="checkbox-custom checkbox-success checkbox-lg checkbox-custom-left">
                    <input type="radio" name="itens[{{$loop->index}}][status_check]" value="S" id="status_check_s{{$loop->index}}"{{ $item->status_check == 'S' ? ' checked' : '' }} />
                    <label for="status_check_s{{$loop->index}}"></label>
                  </div>

                  <div class="checkbox-custom checkbox-danger checkbox-lg checkbox-custom-right">
                    <input type="radio" name="itens[{{$loop->index}}][status_check]" value="N" id="status_check_n{{$loop->index}}"{{ $item->status_check == 'N' ? ' checked' : '' }} />
                    <label for="status_check_n{{$loop->index}}"></label>
                  </div>

                  <div class="checkbox-custom checkbox-lg checkbox-custom-bottom d-none">
                    <input type="radio" name="itens[{{$loop->index}}][status_check]" value="" id="status_check_u{{$loop->index}}"{{ $item->status_check == '' ? ' checked' : '' }} />
                    <label for="status_check_u{{$loop->index}}"></label>
                  </div>

                  @if (file_exists('fotos/'.$item->foto))
                    <div class="image-wrap" data-mfp-src="{{ asset('fotos/'.$item->foto) }}">
                      <img class="image img-rounded" src="{{ route('thumbnail', ['src' => 'fotos/'.$item->foto, 'height' => 528]) }}" alt="{{ $item->foto }}">
                    </div>
                  @else
                    <div class="image-wrap" data-mfp-src="{{ asset('assets/photos/placeholder.png') }}">
                      <img class="image img-rounded" src="{{ route('thumbnail', ['src' => 'assets/photos/placeholder.png', 'height' => 528]) }}" alt="{{ $item->foto }}">
                    </div>
                  @endif
                  <div class="info-wrap">
                    <div class="title text-center"><h4>{{ $item->produto->descricao ?? '' }}</h4></div>
                    <table class="table table-bordered">
                      <tr>
                        <td class="p-10 font-weight-500 text-right" style="width: 15%">Material:</td>
                        <td class="p-10" style="width: 85%">{{ $item->material->descricao ?? '' }} {{ $item->milesimos ? "$item->milesimos mil" : '' }}</td>
                      </tr>
                      <tr>
                        <td class="p-10 font-weight-500 text-right" style="width: 15%">Peso:</td>
                        <td class="p-10" style="width: 85%">{{ number_format ($item->peso, 2, ',', '.') }} g</td>
                      </tr>
                      <tr>
                        <td class="p-10 font-weight-500 text-right" style="width: 15%">Fornecedor:</td>
                        <td class="p-10" style="width: 85%">{{ $item->fornecedor->nome ?? '' }}</td>
                      </tr>
                      <tr>
                        <td class="p-10 font-weight-500 text-right" style="width: 15%">Referência:</td>
                        <td class="p-10" style="width: 85%">{{ $item->referencia }}</td>
                      </tr>
                      <tr>
                        <td class="p-10 font-weight-500 text-right" style="width: 15%">Bruto:</td>
                        <td class="p-10" style="width: 85%">
                          @if ($item->desconto)
                            <span style="text-decoration:line-through;">
                              R$ {{ number_format ($item->preco_bruto, 2, ',', '.') }}
                            </span>
                            <span class="text-danger font-weight-400">R$ {{ number_format ($item->valor_com_desconto, 2, ',', '.') }}</span>
                          @else
                            R$ {{ number_format ($item->preco_bruto, 2, ',', '.') }}
                          @endif

                        </td>
                      </tr>
                      <tr>
                        <td class="p-10 font-weight-500 text-right" style="width: 15%">Banho:</td>
                        <td class="p-10" style="width: 85%">R$ {{ number_format ($item->custo_total, 2, ',', '.') }}</td>
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
              <button type="submit" class="btn btn-success"><i class="icon wb-check"></i> Salvar</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
