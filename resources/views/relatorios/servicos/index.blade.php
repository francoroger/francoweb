@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/ionrangeslider/ionrangeslider.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ionrangeslider/ion.rangeSlider.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/panel.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/ionrangeslider.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript">
  $('#filter-form').on('submit', function(event) {
    event.preventDefault();
    fetchData();
  });

  $(document).on('click', '.pagination li a', function(event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    fetchData(page);
  });

  $(document).on('click', '.dropdown-item', function(event) {
    event.preventDefault();
    var sort = $(this).data('sort-col');
    $('.dropdown-item').each(function(i,item) {
      $(item).removeClass('active');
    });
    $(this).addClass('active');
    $('.dropdown-toggle').html($(this).html());
  });

  function fetchData(page) {
    var formData = new FormData();
    formData.append('dataini', $('#dataini').val());
    formData.append('datafim', $('#datafim').val());
    formData.append('idcliente', $('#idcliente').val().toString());
    formData.append('idguia', $('#idguia').val().toString());
    formData.append('idtiposervico', $('#idtiposervico').val().toString());
    formData.append('idmaterial', $('#idmaterial').val().toString());
    formData.append('idcor', $('#idcor').val().toString());
    formData.append('milesimos', $('#milesimos').val());
    formData.append('sort', $('.dropdown-item.active').data('sort-col'));

    var route = "{{ route('relatorio_servicos.search', '') }}";
    route += page ? "page="+page : '';

    return $.ajax({
      url: route,
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'POST',
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data)
      {
        $('#result').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        alert('erro');
        console.log(jqXHR);
      }
    });
  }
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
          <form id="filter-form" class="form-horizontal" autocomplete="off">
            <div class="form-group row">
              <label class="col-md-2 form-control-label">Período</label>
              <div class="col-md-6">
                <div class="input-daterange" data-plugin="datepicker" data-language="pt-BR">
                  <div class="input-group">
                    <span class="input-group-addon">De</span>
                    <input type="text" class="form-control" name="dataini" id="dataini" />
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Até</span>
                    <input type="text" class="form-control" name="datafim" id="datafim" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Cliente</label>
              <div class="col-md-10">
                <select class="form-control" id="idcliente" name="idcliente" data-plugin="select2" multiple>
                  <option value=""></option>
                  @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Guia</label>
              <div class="col-md-10">
                <select class="form-control" id="idguia" name="idguia" data-plugin="select2" multiple>
                  <option value=""></option>
                  @foreach ($guias as $guia)
                    <option value="{{ $guia->id }}">{{ $guia->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Tipo de Serviço</label>
              <div class="col-md-10">
                <select class="form-control" id="idtiposervico" name="idtiposervico" data-plugin="select2" multiple>
                  <option value=""></option>
                  @foreach ($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->descricao }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Material</label>
              <div class="col-md-10">
                <div class="form-group mb-0">
                  <select class="form-control" id="idmaterial" name="idmaterial" data-plugin="select2" multiple>
                    <option value=""></option>
                    @foreach ($materiais as $material)
                      <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Cor</label>
              <div class="col-md-10">
                <select class="form-control" id="idcor" name="idcor" data-plugin="select2" multiple>
                  <option value=""></option>
                  @foreach ($cores as $cor)
                    <option value="{{ $cor->id }}">{{ $cor->descricao }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 form-control-label">Camada</label>
              <div class="col-md-10">
                <input type="text" id="milesimos" name="milesimos" data-plugin="ionRangeSlider" data-type="double"
                  data-grid=true data-min={{ $milesimos->min() }} data-max={{ $milesimos->max() }}
                  data-from={{ $milesimos->min() }} data-to={{ $milesimos->max() }} />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-10 offset-md-2">
                <button type="submit" class="btn btn-primary"><i class="icon wb-search"></i> Pesquisar</button>
                <div class="dropdown float-right">
                  Ordenar por:
                  <a class="dropdown-toggle inline-block" data-toggle="dropdown" href="#">Data</a>
                  <div class="dropdown-menu animation-scale-up animation-top-right animation-duration-250"
                    role="menu">
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="idservico">Código</a>
                    <a class="active dropdown-item" href="javascript:void(0)" data-sort-col="servico.datavenda">Data</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="cliente.nome">Cliente</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="guia.nome">Guia</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="tiposervico.descricao">Tipo Serviço</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="material.descricao">Material</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="cores.descricao">Cor</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="milesimos">Milésimos</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="valor">Valor</a>
                    <a class="dropdown-item" href="javascript:void(0)" data-sort-col="peso">Peso</a>
                  </div>
                </div>
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
