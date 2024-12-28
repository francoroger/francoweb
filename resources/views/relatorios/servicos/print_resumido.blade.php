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
            <h3>RELATÓRIO DE SERVIÇOS</h3>
            <!-- Fim Título -->
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Conteúdo -->
    <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <thead>
        <tr>
          <th style="width:8%;">Código</th>
          <th style="width:12%;">Data</th>
          <th style="width:33%;">Cliente</th>
          <th style="width:17%;">Guia</th>
          <th style="width:10%;">Valor</th>
          <th style="width:10%;">Comissão</th>
          <th style="width:10%;">Peso (g)</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($servicos as $servico)
          <tr>
            <td class="text-nowrap">{{ $servico->id }}</td>
            <td class="text-nowrap">{{ date('d/m/Y', strtotime($servico->datavenda)) }}</td>
            <td class="text-nowrap">{{ $servico->cliente->identificacao ?? '' }}</td>
            <td class="text-nowrap">{{ $servico->guia->nome ?? '' }}</td>
            <td class="text-nowrap text-right">R$ {{ number_format($servico->itens->sum('valor'), 2, ',', '.') }}</td>
            <td class="text-nowrap text-right">R$ {{ number_format($servico->itens->sum('valor_comis'), 2, ',', '.') }}</td>
            <td class="text-nowrap text-right">{{ number_format($servico->itens->sum('peso'), 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" align="right">Totais:</td>
          <td>R$ {{ number_format($total['valor'], 2, ',', '.') }}</td>
          <td class="text-nowrap text-right">R$ {{ number_format($total['valor_comis'], 2, ',', '.') }}</td>
          <td>{{ number_format($total['peso'], 0, ',', '.') }} g</td>
        </tr>
      </tfoot>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
