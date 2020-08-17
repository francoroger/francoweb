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
        @foreach ($servicos as $servico)
        @php
          $freceb = $servico->recebimentos->sortBy('data_hora_entrada')->first() ?? null;
          $catalog = $servico->catalogacao ?? null;
          $primeira = null;
          $ultima = null;
        @endphp
          <tr>
            <td rowspan="2">{{ $servico->cliente->nome ?? '' }}</td>
            <td>
              @if ($freceb)
                @if ($freceb->carbon_data_hora_entrada)
                  <span class="text-nowrap">{{ $freceb->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                  @php
                    if (!$primeira) {
                      $primeira = $freceb->carbon_data_hora_entrada;
                    }
                    $ultima = $freceb->carbon_data_hora_entrada;
                  @endphp
                @endif
                @if ($servico->carbon_data_hora_entrada)
                  <span class="text-nowrap">{{ $servico->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span>
                  @php
                    if (!$primeira) {
                      $primeira = $servico->carbon_data_hora_entrada;
                    }
                    $ultima = $servico->carbon_data_hora_entrada;
                  @endphp
                @endif
              @endif
            </td>
            <td>
              @if ($servico->carbon_data_hora_entrada->format('d/m/Y H:i'))
                <span class="text-nowrap">{{ $servico->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                @php
                  if (!$primeira) {
                    $primeira = $servico->carbon_data_hora_entrada;
                  }
                  $ultima = $servico->carbon_data_hora_entrada;
                @endphp
              @endif
              @if ($catalog)
                @if ($catalog->carbon_data_hora_entrada)
                  <span class="text-nowrap">{{ $catalog->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span>
                  @php
                    if (!$primeira) {
                      $primeira = $catalog->carbon_data_hora_entrada;
                    }
                    $ultima = $catalog->carbon_data_hora_entrada;
                  @endphp
                @endif
              @endif
            </td>
            <td>
              @if ($catalog)
                @php
                  $catalog_ini = $catalog->historico->where('status', 'A')->first();
                  $catalog_fim = $catalog->historico->where('status', 'A')->where('data_fim', '<>', null)->first();
                @endphp
                @if ($catalog_ini)
                  @if ($catalog_ini->carbon_data_hora_entrada)
                    <span class="text-nowrap">{{ $catalog_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                    @php
                      if (!$primeira) {
                        $primeira = $catalog_ini->carbon_data_hora_entrada;
                      }
                      $ultima = $catalog_ini->carbon_data_hora_entrada;
                    @endphp
                  @endif
                @endif
                @if ($catalog_fim)
                  @if ($catalog_fim->carbon_data_hora_saida)
                    <span class="text-nowrap">{{ $catalog_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                    @php
                      if (!$primeira) {
                        $primeira = $catalog_fim->carbon_data_hora_saida;
                      }
                      $ultima = $catalog_fim->carbon_data_hora_saida;
                    @endphp
                  @endif
                @endif
              @endif
            </td>
            <td>
              @if ($catalog)
                @php
                  $banho_ini = $catalog->historico->where('status', 'F')->first();
                  $banho_fim = $catalog->historico->where('status', 'F')->where('data_fim', '<>', null)->first();
                @endphp
                @if ($banho_ini)
                  @if ($banho_ini->carbon_data_hora_entrada)
                    <span class="text-nowrap">{{ $banho_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                    @php
                      if (!$primeira) {
                        $primeira = $banho_ini->carbon_data_hora_entrada;
                      }
                      $ultima = $banho_ini->carbon_data_hora_entrada;
                    @endphp
                  @endif
                @endif
                @if ($banho_fim)
                  @if ($banho_fim->carbon_data_hora_saida)
                    <span class="text-nowrap">{{ $banho_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                    @php
                      if (!$primeira) {
                        $primeira = $banho_fim->carbon_data_hora_saida;
                      }
                      $ultima = $banho_fim->carbon_data_hora_saida;
                    @endphp
                  @endif
                @endif
              @endif
            </td>
            <td>
              @if ($catalog)
                @php
                  $rev_ini = $catalog->historico->where('status', 'G')->first();
                  $rev_fim = $catalog->historico->where('status', 'G')->where('data_fim', '<>', null)->first();
                @endphp
                @if ($rev_ini)
                  @if ($rev_ini->carbon_data_hora_entrada)
                    <span class="text-nowrap">{{ $rev_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                    @php
                      if (!$primeira) {
                        $primeira = $rev_ini->carbon_data_hora_entrada;
                      }
                      $ultima = $rev_ini->carbon_data_hora_entrada;
                    @endphp
                  @endif
                @endif
                @if ($rev_fim)
                  @if ($rev_fim->carbon_data_hora_saida)
                    <span class="text-nowrap">{{ $rev_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                    @php
                      if (!$primeira) {
                        $primeira = $rev_fim->carbon_data_hora_saida;
                      }
                      $ultima = $rev_fim->carbon_data_hora_saida;
                    @endphp
                  @endif
                @endif
              @endif
            </td>
            <td>
              @if ($catalog)
                @php
                  $exped_ini = $catalog->historico->where('status', 'C')->first();
                  $exped_fim = $catalog->historico->where('status', 'C')->where('data_fim', '<>', null)->first();
                @endphp
                @if ($exped_ini)
                  @if ($exped_ini->carbon_data_hora_entrada)
                    <span class="text-nowrap">{{ $exped_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                    @php
                      if (!$primeira) {
                        $primeira = $exped_ini->carbon_data_hora_entrada;
                      }
                      $ultima = $exped_ini->carbon_data_hora_entrada;
                    @endphp
                  @endif
                @endif
                @if ($exped_fim)
                  @if ($exped_fim->carbon_data_hora_saida)
                    <span class="text-nowrap">{{ $exped_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                    @php
                      if (!$primeira) {
                        $primeira = $exped_fim->carbon_data_hora_saida;
                      }
                      $ultima = $exped_fim->carbon_data_hora_saida;
                    @endphp
                  @endif
                @endif
              @endif
            </td>
            <td>
              @if ($primeira)
                <span class="text-nowrap">{{ $primeira->format('d/m/Y H:i') }}</span><br>
              @endif
              @if ($ultima)
              <span class="text-nowrap">{{ $ultima->format('d/m/Y H:i') }}</span><br>
              @endif
            </td>
          </tr>
          <tr>
            <td>
              {{ $freceb ? str_replace('depois', '', str_replace('antes', '', $freceb->carbon_data_hora_entrada->diffForHumans($servico->carbon_data_hora_entrada))) : '' }}
            </td>
            <td>
              {{ $catalog ? str_replace('depois', '', str_replace('antes', '', $servico->carbon_data_hora_entrada->diffForHumans($catalog->carbon_data_hora_entrada))) : '' }}
            </td>
            <td>
              @if ($catalog_ini && $catalog_fim)
                {{ str_replace('depois', '', str_replace('antes', '', $catalog_ini->carbon_data_hora_entrada->diffForHumans($catalog_fim->carbon_data_hora_saida))) }}
              @endif
            </td>
            <td>
              @if ($banho_ini && $banho_fim)
                {{ str_replace('depois', '', str_replace('antes', '', $banho_ini->carbon_data_hora_entrada->diffForHumans($banho_fim->carbon_data_hora_saida))) }}
              @endif
            </td>
            <td>
              @if ($rev_ini && $rev_fim)
                {{ str_replace('depois', '', str_replace('antes', '', $rev_ini->carbon_data_hora_entrada->diffForHumans($rev_fim->carbon_data_hora_saida))) }}
              @endif
            </td>
            <td>
              @if ($exped_ini && $exped_fim)
                {{ str_replace('depois', '', str_replace('antes', '', $exped_ini->carbon_data_hora_entrada->diffForHumans($exped_fim->carbon_data_hora_saida))) }}
              @endif
            </td>
            <td>
              @if ($primeira && $ultima)
                <strong>{{ $primeira->diffinHours($ultima) }} horas</strong>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
