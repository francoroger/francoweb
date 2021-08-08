@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/modules/css/catalogacao_checklist/check.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/ladda/ladda.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/magnific-popup/magnific-popup.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
  <style media="screen">
    .modal-open .select2-container {
      z-index: 1701 !important;
    }

    .text-strike {
      text-decoration: line-through;
    }

  </style>
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/vendor/isotope/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ladda/spin.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ladda/ladda.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/modules/js/catalogacao_checklist/check.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/filterable.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/ladda.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script>
    function limpaCampos() {
      $('#edit_id').val('');
      $('#edit_index').val('');
      $('#edit_idfornec').val('');
      $('#edit_idfornec').trigger('change');
      $('#edit_peso').val('');
      $('#edit_referencia').val('');
      $('#edit_quantidade').val('');
    }

    $(document).on('click', '.edit-catalog', function(event) {
      event.preventDefault();
      limpaCampos();
      let id = $(this).data('id');
      let index = $(this).data('index');
      $.ajax({
        url: "{{ route('catalogacao_checklist.editItem', ['id' => '/']) }}/" + id,
        type: 'GET',
        success: function(data) {
          $('#edit_id').val(data.id);
          $('#edit_index').val(index);
          $('#edit_idfornec').val(data.idfornec);
          $('#edit_idfornec').trigger('change');
          $('#edit_peso').val(data.peso);
          $('#edit_referencia').val(data.referencia);
          $('#edit_quantidade').val(data.quantidade);
        },
        error: function(jqXHR, textStatus, err) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseText);
        }
      });

      $('#modalEdicao').modal('show');
    });

    $(document).on('click', '#btn-update', function(event) {
      event.preventDefault();

      let id = $('#edit_id').val();

      $.ajax({
        url: "{{ route('catalogacao_checklist.updateItem', ['id' => '/']) }}/" + id,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': $('#edit_id').val(),
          'index': $('#edit_index').val(),
          'idfornec': $('#edit_idfornec').val(),
          'peso': $('#edit_peso').val(),
          'referencia': $('#edit_referencia').val(),
          'quantidade': $('#edit_quantidade').val(),
        },
        success: function(data) {
          $(`div[data-index="${data.index}"]`).html(data.view);
          $('#modalEdicao').modal('hide');
        },
        error: function(jqXHR, textStatus, err) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseText);

        }
      });
    });
  </script>
@endpush

@section('body-class', 'app-media')

@section('content')
<div class="page">
  <form id="check-form" action="{{ route('catalogacao_checklist.update', $catalogacao->id) }}" method="POST"
    autocomplete="off">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="{{ $catalogacao->id }}">
    <input type="hidden" id="status" name="status" value="C">
    {{ csrf_field() }}

    <div class="page-header">
      <div class="row">
        <div class="col-md-9">
          <h1 class="page-title">Catalogação #{{ $catalogacao->id }}</h1>
          <p class="page-description">{{ $catalogacao->cliente->identificacao ?? '' }}</p>
        </div>
        <div class="col-md-3 text-right">
          <!-- Ações -->
          <button type="button" id="btn-autosave" class="btn btn-default btn-sm ladda-button"
            data-url="{{ route('catalogacao_checklist.autosave') }}" data-token="{{ csrf_token() }}"
            data-style="expand-left" data-size="xs" disabled>
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
            <button class="btn btn-block btn-secondary dropdown-toggle selected-item" type="button" id="produtos-menu"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Todos os Produtos
            </button>
            <div class="dropdown-menu w-full animation-scale-up animation-top-left animation-duration-250 exampleFilter"
              aria-labelledby="produtos-menu" role="menu">
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="*">Todos os
                Produtos</a>
              <div class="dropdown-divider"></div>
              @foreach ($produtos as $i => $produto)
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                  data-filter="Produto_{{ $i }}">{{ $produto }}</a>
              @endforeach
            </div>
          </div>
          <div class="fspacer mx-5 my-5"></div>
          <div class="fdropdown dropdown">
            <button class="btn btn-block btn-secondary dropdown-toggle selected-item" type="button" id="materiais-menu"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Todos os Materiais
            </button>
            <div class="dropdown-menu w-full animation-scale-up animation-top-left animation-duration-250 exampleFilter"
              aria-labelledby="materiais-menu" role="menu">
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="*">Todos os
                Materiais</a>
              <div class="dropdown-divider"></div>
              @foreach ($materiais as $i => $material)
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                  data-filter="Material_{{ $i }}">{{ $material }}</a>
              @endforeach
            </div>
          </div>
          <div class="fspacer mx-5 my-5"></div>
          <div class="fdropdown dropdown">
            <button class="btn btn-block btn-secondary dropdown-toggle selected-item" type="button" id="status-menu"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Todas as Situações
            </button>
            <div class="dropdown-menu w-full animation-scale-up animation-top-left animation-duration-250 exampleFilter"
              aria-labelledby="status-menu" role="menu">
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1" data-filter="*">Todas as
                Situações</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                data-filter="Status_Verificado">Verificados</a>
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                data-filter="Status_Aprovado">Aprovados</a>
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                data-filter="Status_Reprovado">Reprovados</a>
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                data-filter="Status_NaoVerificado">Não Verificados</a>
              <a class="dropdown-item" href="javascript:void(0)" role="menuitem" tabindex="-1"
                data-filter="Status_Externo">Serviço Externo</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="mediaContent" class="page-content page-content-table">
      <div class="media-list is-grid" data-child="li">
        <ul class="blocks blocks-100 blocks-xxl-4 blocks-lg-3 blocks-md-2" id="itens_catalogo">
          @foreach ($itens as $item)
            <li
              class="{{ $item->idmaterial ? 'Material_' . $item->idmaterial : '' }} {{ $item->idproduto ? 'Produto_' . $item->idproduto : '' }}{{ $item->status_check == 'S' || $item->status_check == 'N' ? ' Status_Verificado' : ' Status_NaoVerificado' }}{{ $item->status_check == 'S' ? ' Status_Aprovado' : '' }}{{ $item->status_check == 'N' ? ' Status_Reprovado' : '' }}{{ $item->status_check == 'E' ? ' Status_Externo' : '' }}">
              <input type="hidden" name="itens[{{ $loop->index }}][id]" value="{{ $item->id }}">
              <div
                class="media-item bg-white{{ $item->status_check == 'S' ? ' bg-green-100' : '' }}{{ $item->status_check == 'N' ? ' bg-red-100' : '' }}{{ $item->status_check == 'E' ? ' bg-blue-100' : '' }}">
                <div class="checkbox-custom checkbox-success checkbox-lg checkbox-custom-left">
                  <input type="radio" name="itens[{{ $loop->index }}][status_check]" value="S"
                    id="status_check_s{{ $loop->index }}" {{ $item->status_check == 'S' ? ' checked' : '' }} />
                  <label for="status_check_s{{ $loop->index }}"></label>
                </div>

                <div class="checkbox-custom checkbox-danger checkbox-lg checkbox-custom-right">
                  <input type="radio" name="itens[{{ $loop->index }}][status_check]" value="N"
                    id="status_check_n{{ $loop->index }}" {{ $item->status_check == 'N' ? ' checked' : '' }} />
                  <label for="status_check_n{{ $loop->index }}"></label>
                </div>

                <div class="checkbox-custom checkbox-info checkbox-lg checkbox-custom-bottom-left">
                  <input type="radio" name="itens[{{ $loop->index }}][status_check]" value="E"
                    id="status_check_e{{ $loop->index }}" {{ $item->status_check == 'E' ? ' checked' : '' }} />
                  <label for="status_check_e{{ $loop->index }}"></label>
                </div>

                <div class="checkbox-custom checkbox-lg checkbox-custom-bottom-right d-none">
                  <input type="radio" name="itens[{{ $loop->index }}][status_check]" value=""
                    id="status_check_u{{ $loop->index }}" {{ $item->status_check == '' ? ' checked' : '' }} />
                  <label for="status_check_u{{ $loop->index }}"></label>
                </div>

                @if (file_exists('fotos/' . $item->foto))
                  <div class="image-wrap" data-mfp-src="{{ asset('fotos/' . $item->foto) }}">
                    <img class="image img-rounded"
                      src="{{ route('thumbnail', ['src' => 'fotos/' . $item->foto, 'height' => 528]) }}"
                      alt="{{ $item->foto }}">
                  </div>
                @else
                  <div class="image-wrap" data-mfp-src="{{ asset('assets/photos/placeholder.png') }}">
                    <img class="image img-rounded"
                      src="{{ route('thumbnail', ['src' => 'assets/photos/placeholder.png', 'height' => 528]) }}"
                      alt="{{ $item->foto }}">
                  </div>
                @endif
                <div class="info-wrap">
                  <div class="title text-center">
                    <h4>{{ $item->descricao_produto }}</h4>
                  </div>
                  <div class="info-catalog" data-index="{{ $loop->index }}">
                    @include('catalogacao_checklist._info', ['item' => $item, 'index' => $loop->index, 'tiposFalha' =>
                    $tiposFalha])
                  </div>
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

<!-- Modal Edição -->
<div class="modal fade" id="modalEdicao" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true"
  data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEdicaoLabel">Alterar Catalogação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="" autocomplete="off">
          <input type="hidden" id="edit_id" name="edit_id">
          <input type="hidden" id="edit_index" name="edit_index">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="edit_idfornec">Fornecedor</label>
              <select class="form-control" id="edit_idfornec" name="edit_idfornec" data-plugin="select2">
                <option value=""></option>
                @foreach ($fornecedores as $fornecedor)
                  <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-4">
              <label class="form-control-label" for="edit_peso">Peso</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_peso" name="edit_peso" min="0" step="any" />
                <span class="input-group-addon">g</span>
              </div>
            </div>

            <div class="form-group col-md-4">
              <label class="form-control-label" for="edit_referencia">Referência</label>
              <input type="text" class="form-control" id="edit_referencia" name="edit_referencia" />
            </div>

            <div class="form-group col-md-4">
              <label class="form-control-label" for="edit_quantidade">Quantidade</label>
              <input type="number" class="form-control" id="edit_quantidade" name="edit_quantidade" min="0"
                step="any" />
            </div>

          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btn-update">Salvar Alterações</button>
      </div>
    </div>
  </div>
</div>
@endsection
