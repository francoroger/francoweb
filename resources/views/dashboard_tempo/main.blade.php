@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/ladda/ladda.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ladda/spin.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/ladda/ladda.min.js') }}"></script>
@endpush

@push('scripts_page')
  <!-- Apex Charts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/ladda.js') }}"></script>
  <script type="text/javascript">
    $('#dataini').mask('00/00/0000');
    $('#datafim').mask('00/00/0000');

    $('#filtros').on('submit', function(e) {
      e.preventDefault();

      //Ladda
      var l = Ladda.create(document.querySelector('#btn-search'));
      l.start();
      l.isLoading();
      l.setProgress(0 - 1);

      let formData = new FormData();
      formData.append("dataini", $("#dataini").val());
      formData.append("datafim", $("#datafim").val());

      return $.ajax({
        url: "{{ route('dashboard_tempo.search') }}",
        headers: {
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          l.stop();
          $('#total-servicos-count').html(data.total_servicos);
          $('#total-peso-count').html(data.total_peso);
          $('#total-itens-count').html(data.total_itens);
          $('#tempo-total-text').html(data.tempo_total);
          $('#tempo-medio-serv-text').html(data.tempo_medio_serv);
          $('#tempo-medio-item-text').html(data.tempo_medio_item);

          updateChart(data.dados_tempo_exec, tempo_execucao_chart);
          updateChart(data.dados_tempo_espera, tempo_espera_chart);
          updateChart(data.dados_tempo_exec_kg, tempo_kg_chart);
          updateChart(data.dados_tempo_exec_util, tempo_execucao_chart_util);
          updateChart(data.dados_tempo_espera_util, tempo_espera_chart_util);
          updateChart(data.dados_tempo_exec_kg_util, tempo_kg_chart_util);

          $('#dados').html(data.view);

        },
        error: function(jqXHR, textStatus, errorThrown) {
          window.toastr.error(jqXHR.responseJSON.message);
          console.log(jqXHR);
        }
      });

    });


    $(document).ready(function() {
      // Gráfico Média tempo de execução
      var options = {
        stroke: {
          width: 1,
          show: true,
          colors: ['rgb(245, 126, 39)'],
        },
        chart: {
          height: 400,
          type: 'bar'
        },
        series: [{
          data: []
        }],
        xaxis: {
          categories: [''],
          labels: {
            rotate: 0
          }
        },
        yaxis: {
          forceNiceScale: true
        },
        legend: {
          show: false
        },
        dataLabels: {
          formatter: function(val, opt) {
            return val + ' horas'
          },
          style: {
            colors: ['#000000', '#000000']
          }
        },
        fill: {
          type: 'fill',
          opacity: 0.3,
          colors: ['#f57e27']
        }
      }

      var utilOptions = {
        stroke: {
          width: 1,
          show: true,
          colors: ['rgb(62, 142, 247)'],
        },
        chart: {
          height: 400,
          type: 'bar'
        },
        series: [{
          data: []
        }],
        xaxis: {
          categories: [''],
          labels: {
            rotate: 0
          }
        },
        yaxis: {
          forceNiceScale: true
        },
        legend: {
          show: false
        },
        dataLabels: {
          formatter: function(val, opt) {
            return val + ' horas'
          },
          style: {
            colors: ['#000000', '#000000']
          }
        },
        fill: {
          type: 'fill',
          opacity: 0.3,
          colors: ['#3e8ef7']
        }
      }

      tempo_execucao_chart = new ApexCharts(document.querySelector('#chart-tempo-medio-etapa'), options);
      tempo_execucao_chart.render();

      tempo_espera_chart = new ApexCharts(document.querySelector('#chart-tempo-espera-etapa'), options);
      tempo_espera_chart.render();

      tempo_kg_chart = new ApexCharts(document.querySelector('#chart-tempo-medio-kg'), options);
      tempo_kg_chart.render();

      tempo_execucao_chart_util = new ApexCharts(document.querySelector('#chart-tempo-medio-etapa-util'),
        utilOptions);
      tempo_execucao_chart_util.render();

      tempo_espera_chart_util = new ApexCharts(document.querySelector('#chart-tempo-espera-etapa-util'), utilOptions);
      tempo_espera_chart_util.render();

      tempo_kg_chart_util = new ApexCharts(document.querySelector('#chart-tempo-medio-kg-util'), utilOptions);
      tempo_kg_chart_util.render();
    });

    function updateChart(_data, chart) {
      var data_series = [];
      var data_values = [];
      var colors = [];
      for (var k in _data) {
        data_series[k] = _data[k][0];
        data_values[k] = _data[k][1];
      }

      chart.updateOptions({
        colors: colors,
        xaxis: {
          categories: data_series,
        }
      });
      chart.updateSeries([{
        name: 'Tempo médio',
        data: data_values
      }]);
    }

  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Tempo de Execução de Serviços</h1>
      <div class="page-header-actions">
        <form class="form-inline" id="filtros">
          <div class="form-group">
            <div class="input-daterange" data-plugin="datepicker" data-language="pt-BR">
              <div class="input-group">
                <span class="input-group-addon font-size-12">De</span>
                <input type="text" class="form-control font-size-12" name="dataini" id="dataini"
                  value="{{ \Carbon\Carbon::now()->firstOfMonth()->format('d/m/Y') }}" />
              </div>
              <div class="input-group">
                <span class="input-group-addon font-size-12">a</span>
                <input type="text" class="form-control font-size-12" name="datafim" id="datafim"
                  value="{{ \Carbon\Carbon::now()->endOfMonth()->format('d/m/Y') }}" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" id="btn-search" class="btn btn-primary ladda-button" data-style="expand-left">
              <span class="ladda-label">
                <i class="icon wb-search mr-10" aria-hidden="true"></i> Pesquisar
              </span>
            </button>
          </div>

        </form>
      </div>
    </div>

    <div class="page-content">

      <div class="row">
        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number" id="total-servicos-count">0</span>
              <div class="counter-label text-uppercase">serviços</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number"><span id="total-peso-count">0</span> <span
                  class="font-size-12">Kg</span></span>
              <div class="counter-label text-uppercase">peso total</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number" id="total-itens-count">0</span>
              <div class="counter-label text-uppercase">itens</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number"><span id="tempo-total-text">0</span> <span
                  class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo total</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number"><span id="tempo-medio-serv-text">0</span>
                <span class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo médio por serviço</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number"><span id="tempo-medio-item-text">0</span>
                <span class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo médio por item</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo Médio em horas por Etapa (Geral)</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="chart-tempo-medio-etapa" style="height: 420px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo médio de espera em horas entre etapas (Geral)</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="chart-tempo-espera-etapa" style="height: 420px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo Médio de execução (Horas por Kg)</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="chart-tempo-medio-kg" style="height: 420px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo Médio útil em horas por Etapa (Geral)</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="chart-tempo-medio-etapa-util" style="height: 420px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo médio útil de espera em horas entre etapas (Geral)</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="chart-tempo-espera-etapa-util" style="height: 420px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo Médio útil de execução (Horas por Kg)</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="chart-tempo-medio-kg-util" style="height: 420px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div id="dados">
      </div>

    </div>

  </div>
@endsection
