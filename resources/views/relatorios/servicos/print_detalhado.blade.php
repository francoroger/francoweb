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
          <th style="width:5%;">Código</th>
          <th style="width:8%;">Data</th>
          <th style="width:20%;">Cliente</th>
          <th style="width:12%;">Guia</th>
          <th style="width:11%;">Tipo Serviço</th>
          <th style="width:11%;">Material</th>
          <th style="width:8%;">Cor</th>
          <th style="width:3%;">Ml</th>
          <th style="width:8%;">Valor</th>
          <th style="width:7%;">Peso (g)</th>
          <th style="width:7%;">Cons.(g)</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($itens as $item)
          <tr>
            <td>{{ $item->servico->id }}</td>
            <td>{{ date('d/m/Y', strtotime($item->servico->datavenda)) }}</td>
            <td>{{ $item->servico->cliente->identificacao ?? '' }}</td>
            <td>{{ $item->servico->guia->nome ?? '' }}</td>
            <td>{{ $item->tipo_servico->descricao ?? '' }}</td>
            <td>{{ $item->material->descricao ?? '' }}</td>
            <td>{{ $item->cor->descricao ?? '' }}</td>
            <td>{{ $item->milesimos }}</td>
            <td>R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
            <td>{{ number_format($item->peso, 0, ',', '.') }}</td>
            <td>{{ number_format(($item->peso * $item->milesimos) / 1000, 2, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" align="right">Totais:</td>
          <td>R$ {{ number_format($total['valor'], 2, ',', '.') }}</td>
          <td>{{ number_format($total['peso'], 2, ',', '.') }} Kg</td>
          <td></td>
        </tr>
      </tfoot>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
