<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN""http://www.w3.org/TR/REC-html40/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
  <title>Relatório de Retrabalhos</title>

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
      table-layout: fixed;
    }

    .table-bordered td,
    .table-bordered th {
      border: 1px solid #263238;
      padding: .4rem;
      font-size: 11px;
    }

    .text-nowrap {
      white-space: nowrap !important;
    }

    .text-right {
      text-align: right !important;
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
          <h3>RETRABALHOS</h3>
          <!-- Fim Título -->
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Conteúdo -->
  <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <thead>
      <tr>
        <th style="width: 10%;">Código</th>
        <th style="width: 10%;">Data Início</th>
        <th style="width: 10%;">Data Fim</th>
        <th style="width: 50%;">Cliente</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 5%;" class="text-right">Itens</th>
        <th style="width: 5%;" class="text-right">Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($retrabalhos as $retrabalho)
        <tr>
          <td class="text-nowrap">{{ $retrabalho->id }}</td>
          <td class="text-nowrap">
            {{ $retrabalho->data_inicio ? date('d/m/Y', strtotime($retrabalho->data_inicio)) : '' }}</td>
          <td class="text-nowrap">{{ $retrabalho->data_fim ? date('d/m/Y', strtotime($retrabalho->data_fim)) : '' }}
          </td>
          <td class="text-nowrap">{{ $retrabalho->cliente->identificacao ?? '' }}</td>
          <td>
            @switch($retrabalho->status)
              @case('G') Aguardando @break
              @case('A') Em Andamento @break
              @case('E') Concluído @break
              @default Aguardando
            @endswitch
          </td>
          <td class="text-nowrap text-right">{{ $retrabalho->itens->count() }}</td>
          <td class="text-nowrap text-right">{{ number_format($retrabalho->itens->sum('peso'), 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td colspan="5">Totais:</td>
        <td class="text-nowrap text-right">{{ number_format($total['itens'], 0, ',', '.') }}</td>
        <td class="text-nowrap text-right">{{ number_format($total['peso'], 0, ',', '.') }} g</td>
      </tr>
    </tfoot>
  </table>
  <!-- Fim Conteúdo -->

</body>

</html>
