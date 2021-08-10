@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/asspinner/asSpinner.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/asspinner/jquery-asSpinner.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/modules/js/relatorios/retrabalho.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/panel.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/asspinner.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/toastr.js') }}"></script>
  <script type="text/javascript">
    var token = "{{ csrf_token() }}";
    var route = "{{ route('relatorio_retrabalho.preview', '') }}";

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
    <div class="page-content">

      <div class="panel" data-plugin="panel">
        <div class="panel-heading">
          <h3 class="panel-title">Filtros</h3>
          <div class="panel-actions panel-actions-keep">
            <a class="panel-action icon wb-minus" data-toggle="panel-collapse"></a>
          </div>
        </div>
        <div class="panel-body">
          <form id="filter-form" method="POST" action="{{ route('relatorio_retrabalho.print') }}" target="_blank"
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
              <label class="col-md-2 form-control-label">Tipo de Falha</label>
              <div class="col-md-10">
                <div class="input-group">
                  <select class="form-control" id="idtipofalha" name="idtipofalha[]" data-plugin="select2" multiple>
                    <option value=""></option>
                    @foreach ($tiposFalha as $tipoFalha)
                      <option value="{{ $tipoFalha->id }}">{{ $tipoFalha->descricao }}</option>
                    @endforeach
                  </select>
                  <button class="input-group-addon select2-all">Adicionar todos</button>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Tipo de Serviço</label>
              <div class="col-md-10">
                <div class="input-group">
                  <select class="form-control" id="idtiposervico" name="idtiposervico[]" data-plugin="select2" multiple>
                    <option value=""></option>
                    @foreach ($tipos as $tipo)
                      <option value="{{ $tipo->id }}">{{ $tipo->descricao }}</option>
                    @endforeach
                  </select>
                  <button class="input-group-addon select2-all">Adicionar todos</button>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Material</label>
              <div class="col-md-10">
                <div class="form-group mb-0">
                  <div class="input-group">
                    <select class="form-control" id="idmaterial" name="idmaterial[]" data-plugin="select2" multiple>
                      <option value=""></option>
                      @foreach ($materiais as $material)
                        <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                      @endforeach
                    </select>
                    <button class="input-group-addon select2-all">Adicionar todos</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Cor</label>
              <div class="col-md-10">
                <div class="input-group">
                  <select class="form-control" id="idcor" name="idcor[]" data-plugin="select2" multiple>
                    <option value=""></option>
                    @foreach ($cores as $cor)
                      <option value="{{ $cor->id }}">{{ $cor->descricao }}</option>
                    @endforeach
                  </select>
                  <button class="input-group-addon select2-all">Adicionar todos</button>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Camada</label>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon">De</span>
                  <input type="text" class="form-control" name="milini" id="milini" data-plugin="asSpinner" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon">Até</span>
                  <input type="text" class="form-control" name="milfim" id="milfim" data-plugin="asSpinner" />
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Modelo</label>
              <div class="col-md-10">
                <select class="form-control" id="modelo" name="modelo">
                  <option value="D">Detalhado</option>
                  <option value="R">Resumido</option>
                  <option value="A">Agrupado</option>
                </select>
              </div>
            </div>

            <div id="sorter-det" class="form-group row">
              <label class="col-md-2 form-control-label">Ordenar por</label>
              <div class="col-md-10">
                <select class="form-control" id="sortbydet" name="sortbydet">
                  <option value="retrabalhos.id">Código</option>
                  <option value="retrabalhos.data_inicio">Data</option>
                  <option value="cliente.nome">Cliente</option>
                  <option value="tipo_falha.descricao">Tipo de Falha</option>
                  <option value="tipo_servico.descricao">Tipo Serviço</option>
                  <option value="material.descricao">Material</option>
                  <option value="cor.descricao">Cor</option>
                  <option value="milesimos">Milésimos</option>
                  <option value="peso">Peso</option>
                </select>
              </div>
            </div>

            <div id="sorter-res" class="form-group row d-none">
              <label class="col-md-2 form-control-label">Ordenar por</label>
              <div class="col-md-10">
                <select class="form-control" id="sortbyres" name="sortbyres">
                  <option value="retrabalhos.id">Código</option>
                  <option value="data_inicio">Data</option>
                  <option value="cliente.nome">Cliente</option>
                </select>
              </div>
            </div>

            <div id="group-by" class="form-group row d-none">
              <label class="col-md-2 form-control-label">Agrupar por</label>
              <div class="col-md-10">
                <select class="form-control" id="grupos" name="grupos[]" data-plugin="select2" multiple>
                  <option value=""></option>
                  <option value="retrabalhos.data_inicio">Data</option>
                  <option value="tipo_falha.descricao">Tipo de Falha</option>
                  <option value="tipo_servico.descricao">Tipo Serviço</option>
                  <option value="material.descricao">Material</option>
                  <option value="cor.descricao">Cor</option>
                  <option value="milesimos">Milésimos</option>
                </select>
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
