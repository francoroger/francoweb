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
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/datatables.js') }}"></script>
  <script src="{{ asset('assets/modules/js/tanques/index.js') }}"></script>

  <script src="{{ asset('assets/js/Plugin/bootbox.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/toastr.js') }}"></script>

  <script src="{{ asset('assets/examples/js/advanced/bootbox-sweetalert.js') }}"></script>

  <script type="text/javascript">
    function excluirTanque(id)
    {
      if (id) {
        $.ajax({
          url: "{{ route('tanques.destroy', '') }}/" + id,
          headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
          type: 'DELETE',
          dataType: "json",
          success: function (data)
          {
            var el = $("#tanques-table").find("[data-id='" + id + "']");
            var row = el.parent().parent().parent();
            removeTableRow(row);
          },
          error: function(jqXHR, textStatus, errorThrown)
          {
            window.toastr.error(jqXHR.responseJSON.message)
            console.log(jqXHR);
          }
        });
      }
    }
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Tanques</h1>
      <div class="page-header-actions">
        <div class="btn-group btn-group-sm" aria-label="Ações" role="group">
          <a href="{{ route('tanques.create') }}" class="btn btn-info">
            <i class="icon wb-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Adicionar</span>
          </a>
        </div>
      </div>
    </div>

    <div class="page-content">
      <div class="panel">
        <div class="panel-body">
          <table class="table table-hover dataTable table-striped w-full" id="tanques-table" data-ajax="{{ route('tanques.ajax') }}" data-processing="true">
            <thead>
              <tr>
                <th>Tanque</th>
                <th>Ciclo de Reforço (gramas)</th>
                <th>Tipo de Consumo</th>
                <th>Desconto Mil.</th>
                <th style="width:100px;" class="text-center">Ações</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
