<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN""http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>Relatório Check List Catalogação</title>

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
            <h3>CHECK LIST CATALOGAÇÃO</h3>
            <!-- Fim Título -->
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Conteúdo -->
    <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <thead>
        <tr>
          <th style="width:10%">Código</th>
          <th style="width:10%">Data</th>
          <th style="width:30%">Cliente</th>
          <th style="width:10%">Status</th>
          <th style="width:10%">Itens</th>
          <th style="width:10%">Checados</th>
          <th style="width:10%">Conforme</th>
          <th style="width:10%">Não Conforme</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($catalogacoes as $catalogacao)
        <tr>
          <td>{{ $catalogacao->id }}</td>
          <td>{{ date('d/m/Y H:i', strtotime($catalogacao->datacad . ' ' . $catalogacao->horacad)) }}</td>
          <td>{{ $catalogacao->cliente->identificacao ?? '' }}</td>
          <td>
            @switch($catalogacao->status)
              @case('F') Banho/Preparação @break
              @case('G') Aguardando @break
              @case('P') Em Andamento @break
              @case('C') Concluída @break
              @case('L') Concluída @break
              @default 
            @endswitch
          </td>
          <td>{{ $catalogacao->itens->count() }}</td>
          <td>{{ $catalogacao->itens->where('status_check')->count() }}</td>
          <td>{{ $catalogacao->itens->where('status_check', 'S')->count() }}</td>
          <td>{{ $catalogacao->itens->where('status_check', 'N')->count() }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
