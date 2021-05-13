@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/asspinner/asSpinner.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/ionrangeslider/ionrangeslider.min.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/asspinner/jquery-asSpinner.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ionrangeslider/ion.rangeSlider.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/modules/js/relatorios/tempo_execucao.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/panel.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/asspinner.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/ionrangeslider.js') }}"></script>
  <script type="text/javascript">
    var token = "{{ csrf_token() }}";
    var route = "{{ route('relatorio_tempo_execucao.preview', '') }}";

    //Form
    $('#filter-form').on('keypress', function(e) {
      if (e.which == 13) {
        return false;
      }
    });

    //Preview
    $(document).on('click', '#btn-preview', function(event) {
      fetchData(route, token);
    });

    //Pagination
    $(document).on('click', '.pagination li a', function(event) {
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      fetchData(route, token, page);
    });

    $(document).ready(function($) {
      $('#dataini').mask('00/00/0000');
      $('#datafim').mask('00/00/0000');
    });

  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Relatório de Tempo de Execução de Serviços</h1>
    </div>

    <div class="page-content">

      <div class="panel" data-plugin="panel">
        <div class="panel-heading">
          <h3 class="panel-title">Filtros</h3>
          <div class="panel-actions panel-actions-keep">
            <a class="panel-action icon wb-minus" data-toggle="panel-collapse"></a>
          </div>
        </div>
        <div class="panel-body">
          <form id="filter-form" method="POST" action="{{ route('relatorio_tempo_execucao.print') }}" target="_blank"
            class="form-horizontal" autocomplete="off">
            @csrf
            <div class="form-group row">
              <label class="col-md-2 form-control-label">Período</label>
              <div class="col-md-6">
                <div class="input-daterange" data-plugin="datepicker" data-language="pt-BR">
                  <div class="input-group">
                    <span class="input-group-addon">De</span>
                    <input type="text" class="form-control" name="dataini" id="dataini"
                      value="{{ \Carbon\Carbon::now()->firstOfMonth()->format('d/m/Y') }}" />
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Até</span>
                    <input type="text" class="form-control" name="datafim" id="datafim"
                      value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Cliente</label>
              <div class="col-md-10">
                <select class="form-control" id="idcliente" name="idcliente[]" data-plugin="select2" multiple>
                  <option value=""></option>
                  @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->identificacao }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Processo</label>
              <div class="col-md-10">
                <select class="form-control" id="tipo_processo" name="tipo_processo">
                  <option value="">Exibir Todos</option>
                  <option value="1">Somente Processos Completo</option>
                  <option value="0">Somente Processos em Andamento</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Etapas</label>
              <div class="col-md-10">
                <input type="text" id="etapas" name="etapas" data-plugin="ionRangeSlider" data-type="double"
                  data-values="Recebimento,Separação,Catalogação,Preparação,Banho,Revisão,Expedição" data-grid=true
                  data-from="0" data-to="6" />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-10 offset-md-2">
                <button type="button" id="btn-preview" class="btn btn-success">
                  <i class="icon wb-search" aria-hidden="true"></i> Pesquisar
                </button>
                <button type="submit" id="btn-print" name="output" value="print" class="btn btn-info">
                  <i class="fa fa-print" aria-hidden="true"></i> Imprimir
                </button>
                <button type="submit" id="btn-pdf" name="output" value="pdf" class="btn btn-danger">
                  <i class="icon fa-file-pdf-o" aria-hidden="true"></i> Gerar PDF
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="panel">
        <div id="result"></div>
      </div>
    </div>
  </div>
@endsection
