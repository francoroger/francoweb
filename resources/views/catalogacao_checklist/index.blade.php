@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/asspinner/asSpinner.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">

  <style media="screen">
    .modal-open .select2-container {
      z-index: 1701!important;
    }
  </style>
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-rowgroup/dataTables.rowGroup.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-scroller/dataTables.scroller.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-responsive/dataTables.responsive.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/dataTables.buttons.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/buttons.html5.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/buttons.flash.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/buttons.print.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/buttons.colVis.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.js') }}"></script>
  <script src="{{ asset('assets/vendor/asrange/jquery-asRange.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootbox/bootbox.js') }}"></script>

  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
  <script src="{{ asset('assets/vendor/asspinner/jquery-asSpinner.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/datatables.js') }}"></script>
  <!--<script src="{{-- asset('assets/modules/js/catalogacao_checklist/index.js') --}}"></script>-->

  <script src="{{ asset('assets/js/Plugin/bootbox.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/toastr.js') }}"></script>

  <script src="{{ asset('assets/examples/js/advanced/bootbox-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/asspinner.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>

  <script>
    var DTable;

    $(document).ready(function() {
      var defaults = Plugin.getDefaults("dataTable");

      var options = $.extend(true, {}, defaults, {
        columns: [
          { data: 'id' },
          { data: 'cliente' },
          { data: 'datacad' },
          { data: 'horacad' },
          { data: 'status', sClass: "text-center" },
          { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
        ],
        order: [[ 0, 'desc' ]],
        pageLength: 50,
        processing: true,
        ajax:  {
          url: "{{ route('catalogacao_checklist.ajax') }}",
<<<<<<< HEAD
          type: 'POST',
          headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
=======
>>>>>>> 7e5b37cb9f83395d9e052fa4aaa0900d02db0493
          data: function(d) {
            d.idproduto = $('#idproduto').val();
            d.idmaterial = $('#idmaterial').val();
            d.idfornec = $('#idfornec').val();
            d.referencia = $('#referencia').val();
            d.pesoini = $('#pesoini').val();
            d.pesofim = $('#pesofim').val();
            d.status = $('#status').val();
            d.status_check = $('#status_check').val();
          }
        }
      });

      DTable = $('#catalogacao-checklist-table').DataTable(options);
    });

    $(document).on('click', '#btn-pesquisar', function() {      
      $.ajax({
        url: "{{ route('catalogacao_checklist.ajax') }}",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'POST',
        beforeSend: function(jqXHR, settings) {
          $('#btn-pesquisar').prop('disabled', true);
          $('#btn-pesquisar').removeClass('btn-success');
          $('#btn-pesquisar').addClass('btn-default');
        },
        success: function (data)
        {
          DTable.ajax.reload(function() {
            $('#btn-pesquisar').prop('disabled', false);
            $('#btn-pesquisar').removeClass('btn-default');
            $('#btn-pesquisar').addClass('btn-success');
            $('#modalPesquisa').modal('hide');
          }, true);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          window.toastr.error(jqXHR.responseJSON.message)
          console.log(jqXHR);
        }
      }); 
    });
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Check List de Catalogações</h1>
      <div class="page-header-actions">
        <button type="button" class="btn btn-icon btn-info" data-toggle="modal" data-target="#modalPesquisa">
          <i class="fa fa-search"></i>
          <span class="hidden-sm-down ml-3">Pesquisa Avançada</span>
        </button>
      </div>
    </div>

    <div class="page-content">
      <div class="panel">
        <div class="panel-body">
          <table class="table table-hover dataTable table-striped w-full" id="catalogacao-checklist-table">
            <thead>
              <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Hora</th>
                <th class="text-center">Status</th>
                <th class="text-center">Ações</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Pesquisa -->
  <div class="modal fade" id="modalPesquisa" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPesquisaLabel">Pesquisa Avançada</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form action="" autocomplete="off">
            <div class="row">
              <div class="form-group col-md-8">
                <label class="form-control-label" for="idproduto">Produto</label>
                <select class="form-control" id="idproduto" name="idproduto" data-plugin="select2" data-placeholder="TODOS" data-allow-clear="true">
                  <option value=""></option>
                  @foreach ($produtos as $produto)
                    <option value="{{ $produto->id }}">{{ $produto->descricao }}</option>
                  @endforeach
                </select>
              </div>
  
              <div class="form-group col-md-4">
                <label class="form-control-label" for="status">Status</label>
                <select class="form-control" id="status" name="status" data-plugin="select2" data-placeholder="TODOS" data-allow-clear="true">
                  <option value=""></option>
                  <option value="F">Banho/Preparação</option>
                  <option value="G">Aguardando</option>
                  <option value="P">Em Andamento</option>
                  <option value="C">Concluída</option>
                </select>
              </div>
    
              <div class="form-group col-md-8">
                <label class="form-control-label" for="idmaterial">Material</label>
                <select class="form-control" id="idmaterial" name="idmaterial" data-plugin="select2" data-placeholder="TODOS" data-allow-clear="true">
                  <option value=""></option>
                  @foreach ($materiais as $material)
                    <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group col-md-4">
                <label class="form-control-label" for="status_check">Situação</label>
                <select class="form-control" id="status_check" name="status_check" data-plugin="select2" data-placeholder="TODOS" data-allow-clear="true">
                  <option value=""></option>
                  <option value="S">OK</option>
                  <option value="N">Não Conforme</option>
                  <option value="-">Não Checado</option>
                </select>
              </div>
    
              <div class="form-group col-md-8">
                <label class="form-control-label" for="idfornec">Fornecedor</label>
                <select class="form-control" id="idfornec" name="idfornec" data-plugin="select2" data-placeholder="TODOS" data-allow-clear="true">
                  <option value=""></option>
                  @foreach ($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                  @endforeach
                </select>
              </div>
    
              <div class="form-group col-md-4">
                <label class="form-control-label" for="referencia">Referência</label>
                <input type="text" class="form-control" id="referencia" name="referencia" />
              </div>
    
              <div class="form-group col-md-12">
                <label class="form-control-label" for="pesoini">Peso</label>
                <div class="row">
                  <div class="input-group col-6">
                    <span class="input-group-addon">De</span>
                    <input type="number" class="form-control" id="pesoini" name="pesoini" min="0" step="any" />
                  </div>
  
                  <div class="input-group col-6">
                    <span class="input-group-addon">Até</span>
                    <input type="number" class="form-control" id="pesofim" name="pesofim" min="0" step="any" />
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btn-pesquisar"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
      </div>
    </div>
  </div>

@endsection
