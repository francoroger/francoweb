<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN""http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>Relatório Tempo Execução Serviços</title>

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
      .text-nowrap {
        white-space: nowrap!important;
      }
    </style>
  </head>

  <body>

    <footer>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
          <tr>
            <td>
              francogalvanica.com.br
            </td>
            <td style="text-align:right;" class="page-number"></td>
          </tr>
        </tbody>
      </table>
    </footer>

    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody>
        <tr>
          <td width="100%" valign="top" align="center">
            <!-- Título -->
            <h3>TEMPO DE EXECUÇÃO DE SERVIÇOS</h3>
            <!-- Fim Título -->
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Conteúdo -->
    <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <thead>
        <tr>
          <th style="width:23%;">Cliente</th>
          <th style="width:11%;">Recebimento</th>
          <th style="width:11%;">Separação</th>
          <th style="width:11%;">Catalogação</th>
          <th style="width:11%;">Banho</th>
          <th style="width:11%;">Revisão</th>
          <th style="width:11%;">Expedição</th>
          <th style="width:11%;">TOTAL</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($servicos as $separacao)
        @php
          $primeiro = null;
          $ultimo = null;
          //Recebimento
          if ($separacao->recebimentos->sortBy('data_receb')->first()) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->first()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->first()->hora_receb) : '';
          }
          if ($separacao->recebimentos->sortBy('data_receb')->last()) {
            $ultimo = \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->last()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->last()->hora_receb);
          }
          //Separação
          $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->created_at) : '';
          $ultimo = \Carbon\Carbon::parse($separacao->created_at);
          //Catalogação
          if ($separacao->data_inicio_catalogacao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_catalogacao) : '';
          }
          if ($separacao->data_fim_catalogacao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_catalogacao);
          }
          //Preparação
          if ($separacao->data_inicio_preparacao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_preparacao) : '';
          }
          if ($separacao->data_fim_preparacao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_preparacao);
          }
          //Revisão
          if ($separacao->data_inicio_revisao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_revisao) : '';
          }
          if ($separacao->data_fim_revisao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_revisao);
          }
          //Expedição
          if ($separacao->data_inicio_expedicao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_expedicao) : '';
          }
          if ($separacao->data_fim_expedicao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_expedicao);
          }
        @endphp
        <tr>
          <td rowspan="2">{{ $separacao->cliente->identificacao ?? '' }}</td>
          <td>{{ $separacao->recebimentos->sortBy('data_receb')->first() ? \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->first()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->first()->hora_receb)->format('d/m/Y H:i:s') : ''  }}</td>
          <td>{{ \Carbon\Carbon::parse($separacao->created_at)->format('d/m/Y H:i:s') }}</td>
          <td>{{ $separacao->data_inicio_catalogacao ? \Carbon\Carbon::parse($separacao->data_inicio_catalogacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_inicio_preparacao ? \Carbon\Carbon::parse($separacao->data_inicio_preparacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_inicio_revisao ? \Carbon\Carbon::parse($separacao->data_inicio_revisao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_inicio_expedicao ? \Carbon\Carbon::parse($separacao->data_inicio_expedicao)->format('d/m/Y H:i:s') : '' }}</td>
          <td rowspan="2">
            {{ $primeiro->diffForHumans($ultimo, \Carbon\CarbonInterface::DIFF_ABSOLUTE, false, 4) }}
          </td>
        </tr>
        <tr>
          <td>{{ $separacao->recebimentos->sortBy('data_receb')->last() ? \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->last()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->last()->hora_receb)->format('d/m/Y H:i:s') : ''  }}</td>
          <td>{{ $separacao->data_inicio_catalogacao ? \Carbon\Carbon::parse($separacao->data_inicio_catalogacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_catalogacao ? \Carbon\Carbon::parse($separacao->data_fim_catalogacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_preparacao ? \Carbon\Carbon::parse($separacao->data_fim_preparacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_revisao ? \Carbon\Carbon::parse($separacao->data_fim_revisao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_expedicao ? \Carbon\Carbon::parse($separacao->data_fim_expedicao)->format('d/m/Y H:i:s') : '' }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
