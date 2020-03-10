<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN""http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>Relatório de Serviços</title>

    <style type="text/css">
      body * {
        font-family: Arial, sans-serif !important;
      }
      footer {
        position: fixed;
        bottom: -20px;
        left: 0px;
        right: 0px;
        height: 20px;
        font-size: 10px;
      }
      table {
        table-layout: fixed;
      }
      .page-number:before {
        content: counter(page);
      }
      .table-bordered {
        table-layout:fixed;
      }
      .table-bordered td, .table-bordered th {
        border: 1px solid #263238;
        padding: .4rem;
        font-size: 11px;
      }
      .subtitulo-red {
        font-size: 14px;
        font-weight: 600;
        color:#dc3545;
      }
      .subtitulo-blue {
        font-size: 12px;
        font-weight: 600;
        color: #007bff;
      }
      .subtitulo-black {
        font-size: 12px;
        font-weight: 600;
      }
      .text-right {
        text-align: right !important;
      }
      .cell-total {
        border-bottom: none!important;
        border-left: none!important;
      }
      .spacer {
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .item-group {
        page-break-inside: avoid;
      }

    </style>
  </head>

  <body>

    <footer>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
          <tr>
            <td>
              Relatório de Produção
            </td>
            <td style="text-align:right;" class="page-number"></td>
          </tr>
        </tbody>
      </table>
    </footer>

    <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody>
        <tr>
          <td width="100%" valign="top" align="center">
            <!-- Título -->
            <h3>RELATÓRIO DE PRODUÇÃO</h3>
            <!-- Fim Título -->
          </td>
        </tr>
      </tbody>
    </table>

    @php
      //Agrupa por data
      $servicosPorData = $itens['ComCliente']->groupBy('data_serv');
      $sumarioPorData = $itens['SemCliente'];
      $sumarioGeral = $itens['SemCliente'];
    @endphp

    @if ($servicosPorData->count() > 0)
    @foreach ($servicosPorData as $data => $servicosNaData)

      <p class="subtitulo-red">{{ date('d/m/Y', strtotime($data)) }}</p>

      @php
        //Agrupa por clientes
        $servicosPorCliente = $servicosNaData;
        $servicosPorCliente = $servicosPorCliente->sortBy('cliente');
        $servicosPorCliente = $servicosPorCliente->groupBy('cliente');
      @endphp

      @foreach ($servicosPorCliente as $cliente => $servicos)
        <div class="item-group">
          <p class="subtitulo-blue">{!! $cliente == '' ? '<i>Cliente não informado</i>' : $cliente !!}</p>

          <!-- Conteúdo -->
          <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
            <thead>
              <tr>
                <th style="width:25%;">Tipo Serviço</th>
                <th style="width:25%;">Material</th>
                <th style="width:25%;">Cor</th>
                <th style="width:5%;">Ml</th>
                <th style="width:20%;">Peso (g)</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($servicos as $servico)
                <tr>
                  <td>{{ $servico->tipo_servico }}</td>
                  <td>{{ $servico->material }}</td>
                  <td>{{ $servico->variacao_cor }}</td>
                  <td>{{ $servico->milesimos }}</td>
                  <td>{{ number_format($servico->total_peso, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4" class="text-right cell-total"></td>
                <td>Total: {{ $servicos->sum('total_peso') > 1000 ? number_format($servicos->sum('total_peso')/1000, 1, ',', '.') . ' Kg' : number_format($servicos->sum('total_peso'), 0, ',', '.') . ' g' }}</td>
              </tr>
            </tfoot>
          </table>
          <!-- Fim Conteúdo -->
        </div>
      @endforeach

      <div class="item-group">
        <p class="subtitulo-black">RESUMO DO DIA</p>

        <!-- Conteúdo -->
        <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
          <thead>
            <tr>
              <th style="width:25%;">Tipo Serviço</th>
              <th style="width:25%;">Material</th>
              <th style="width:25%;">Cor</th>
              <th style="width:5%;">Ml</th>
              <th style="width:20%;">Peso (g)</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sumarioPorData->where('data_serv', '=', $data) as $servico)
              <tr>
                <td>{{ $servico->tipo_servico }}</td>
                <td>{{ $servico->material }}</td>
                <td>{{ $servico->variacao_cor }}</td>
                <td>{{ $servico->milesimos }}</td>
                <td>{{ number_format($servico->total_peso, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="text-right cell-total"></td>
              <td>Total: {{ $sumarioPorData->where('data_serv', '=', $data)->sum('total_peso') > 1000 ? number_format($sumarioPorData->where('data_serv', '=', $data)->sum('total_peso')/1000, 1, ',', '.') . ' Kg' : number_format($sumarioPorData->where('data_serv', '=', $data)->sum('total_peso'), 0, ',', '.') . ' g' }}</td>
            </tr>
          </tfoot>
        </table>
        <!-- Fim Conteúdo -->
      </div>

      <div class="spacer"></div>

    @endforeach
  @endif

    <div class="item-group">
      <p class="subtitulo-black">RESUMO DO PERÍODO</p>

      <!-- Conteúdo -->
      <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <thead>
          <tr>
            <th style="width:25%;">Tipo Serviço</th>
            <th style="width:25%;">Material</th>
            <th style="width:25%;">Cor</th>
            <th style="width:5%;">Ml</th>
            <th style="width:20%;">Peso (g)</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sumarioGeral as $servico)
            <tr>
              <td>{{ $servico->tipo_servico }}</td>
              <td>{{ $servico->material }}</td>
              <td>{{ $servico->variacao_cor }}</td>
              <td>{{ $servico->milesimos }}</td>
              <td>{{ number_format($servico->total_peso, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" class="text-right cell-total"></td>
            <td>Total: {{ $sumarioGeral->where('data_serv', '=', $data)->sum('total_peso') > 1000 ? number_format($sumarioGeral->where('data_serv', '=', $data)->sum('total_peso')/1000, 1, ',', '.') . ' Kg' : number_format($sumarioGeral->where('data_serv', '=', $data)->sum('total_peso'), 0, ',', '.') . ' g' }}</td>
          </tr>
        </tfoot>
      </table>
      <!-- Fim Conteúdo -->
    </div>

  </body>
</html>
