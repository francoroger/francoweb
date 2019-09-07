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
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/datatables.js') }}"></script>

@endpush

@section('content')
  <div class="page-header">
    <h1 class="page-title font-size-26 font-weight-100">Clientes</h1>
  </div>

  <div class="page-content">
    <div class="panel">
      <div class="panel-body">
        <table class="table table-hover dataTable table-striped w-full" id="myTable" data-plugin="dataTable">
          <thead>
            <tr>
              <th>Nome</th>
              <th>CPF</th>
              <th>Cidade</th>
              <th>UF</th>
              <th>Telefone</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
