@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/examples/css/charts/flot.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/flot/jquery.flot.js') }}"></script>
  <script src="{{ asset('assets/vendor/flot/jquery.flot.resize.js') }}"></script>
  <script src="{{ asset('assets/vendor/flot/jquery.flot.time.js') }}"></script>
  <script src="{{ asset('assets/vendor/flot/jquery.flot.stack.js') }}"></script>
  <script src="{{ asset('assets/vendor/flot/jquery.flot.pie.js') }}"></script>
  <script src="{{ asset('assets/vendor/flot/jquery.flot.selection.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function($) {
      $('#dataini').mask('00/00/0000');
      $('#datafim').mask('00/00/0000');
    });

    (function() {
      var b = [
        [1, 18],
        [2, 6],
        [3, 12],
        [4, 4],
        [5, 9],
        [6, 10],
      ];
      var a = [{
        label: "Fish values",
        data: b
      }];

      $.plot("#tempo-medio-etapa", a, {
        xaxis: {
          axisLabel: "Etapa",
          min: 1,
          max: 6,
          mode: null,
          tickSize: [1, "month"],
          ticks: [
            [1, "Recebimento"],
            [2, "Separação"],
            [3, "Catalogação"],
            [4, "Banho"],
            [5, "Revisão"],
            [6, "Expedição"]
          ],
          tickLength: 0,
          // tickColor: "#edeff2",
          color: "#474e54",
          font: {
            size: 14,
            weight: 300
            // family: "OpenSans Light"
          }
        },
        yaxis: {
          axisLabel: "Tempo",
          tickColor: "#edeff2",
          tickFormatter: function(val, axis) {
            return val + " horas";
          },
          color: "#474e54",
          font: {
            size: 14,
            weight: "300"
            // family: "OpenSans Light"
          }
        },
        series: {
          shadowSize: 0,
          lines: {
            show: true,
            // fill: true,
            // fillColor: "#ff0000",
            lineWidth: 1.5
          },
          points: {
            show: true,
            fill: true,
            fillColor: Config.colors("primary", 600),
            radius: 3,
            lineWidth: 1
          }
        },
        colors: [Config.colors("primary", 400)],
        grid: {
          // show: true,
          hoverable: true,
          clickable: true,
          // color: "green",
          // tickColor: "red",
          backgroundColor: {
            colors: ["#fcfdfe", "#fcfdfe"]
          },
          borderWidth: 0
          // borderColor: "#ff0000"
        },
        legend: {
          show: false
        }
      });
    })();

    (function() {
      var b = [
        [1, 0],
        [2, 6],
        [3, 2],
        [4, 5],
        [5, 7],
        [6, 6],
      ];
      var a = [{
        label: "Fish values",
        data: b
      }];

      $.plot("#tempo-espera-etapa", a, {
        xaxis: {
          axisLabel: "Etapa",
          min: 1,
          max: 5,
          mode: null,
          tickSize: [1, "month"],
          ticks: [
            [1, ""],
            [2, "Receb. x Sep."],
            [3, "Sep. x Cat."],
            [4, "Cat. x Banho"],
            [5, "Banho x Rev."],
            [6, "Rev. x Exp."],
          ],
          tickLength: 0,
          // tickColor: "#edeff2",
          color: "#474e54",
          font: {
            size: 14,
            weight: 300
            // family: "OpenSans Light"
          }
        },
        yaxis: {
          axisLabel: "Tempo",
          tickColor: "#edeff2",
          tickFormatter: function(val, axis) {
            return val + " horas";
          },
          color: "#474e54",
          font: {
            size: 14,
            weight: "300"
            // family: "OpenSans Light"
          }
        },
        series: {
          shadowSize: 0,
          lines: {
            show: true,
            // fill: true,
            // fillColor: "#ff0000",
            lineWidth: 1.5
          },
          points: {
            show: true,
            fill: true,
            fillColor: Config.colors("primary", 600),
            radius: 3,
            lineWidth: 1
          }
        },
        colors: [Config.colors("primary", 400)],
        grid: {
          // show: true,
          hoverable: true,
          clickable: true,
          // color: "green",
          // tickColor: "red",
          backgroundColor: {
            colors: ["#fcfdfe", "#fcfdfe"]
          },
          borderWidth: 0
          // borderColor: "#ff0000"
        },
        legend: {
          show: false
        }
      });
    })();

  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Tempo de Execução de Serviços</h1>
      <div class="page-header-actions">
        <form>
          <div class="input-daterange" data-plugin="datepicker" data-language="pt-BR">
            <div class="input-group">
              <span class="input-group-addon font-size-12">De</span>
              <input type="text" class="form-control font-size-12" name="dataini" id="dataini"
                value="{{ \Carbon\Carbon::now()->firstOfMonth()->format('d/m/Y') }}" />
            </div>
            <div class="input-group">
              <span class="input-group-addon font-size-12">a</span>
              <input type="text" class="form-control font-size-12" name="datafim" id="datafim"
                value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" />
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="page-content">

      <div class="row">
        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number">100</span>
              <div class="counter-label text-uppercase">total serviços</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number">600 <span class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo corrido total</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number">350 <span class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo útil total</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number">25 <span class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo médio por serviço</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number">1500</span>
              <div class="counter-label text-uppercase">total itens</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-block p-25">
            <div class="counter counter-lg">
              <span class="counter-number">2 <span class="font-size-12">horas</span></span>
              <div class="counter-label text-uppercase">tempo médio por item</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo Médio por Etapa</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="tempo-medio-etapa" style="height: 400px;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="example-wrap">
            <h4 class="example-title">Tempo médio de espera entre etapas</h4>
            <div class="example example-responsive">
              <div class="w-xs-400" id="tempo-espera-etapa" style="height: 400px;"></div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@endsection
