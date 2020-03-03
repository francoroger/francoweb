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

    @foreach ($itens as $data => $servicos)

      <p><b>{{ date('d/m/Y', strtotime($data)) }}</b></p>

      <!-- Conteúdo -->
      <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <thead>
          <tr>
            <th style="width:40%;">Cliente</th>
            <th style="width:15%;">Tipo Serviço</th>
            <th style="width:15%;">Material</th>
            <th style="width:15%;">Cor</th>
            <th style="width:5%;">Ml</th>
            <th style="width:10%;">Peso (g)</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($servicos->sortBy('cliente') as $item)
            <tr>
              <td>{{ $item->cliente }}</td>
              <td>{{ $item->tipo_servico }}</td>
              <td>{{ $item->material }}</td>
              <td>{{ $item->cor }}</td>
              <td>{{ $item->milesimos }}</td>
              <td>{{ $item->peso }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <!-- Fim Conteúdo -->

    @endforeach



  </body>
</html>
