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
      .bg-yellow-100 {
        background-color: #fff6b5!important;
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
            <h3>FICHA DE PRODUÇÃO</h3>
            <p><b>{{ $tanque }} - {{ $ciclo }} g</b></p>
            <!-- Fim Título -->
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Conteúdo -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody>
        <tr>
          <td width="50%" valign="top" align="center">

            <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
              <thead>
                <tr>
                  <th style="width:50%;">Data</th>
                  <th style="width:50%;">Peso (g)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($itens[0] as $item)
                  <tr class="{{ $item->tipo == 'R' ? ' bg-yellow-100' : '' }}">
                    <td>{{ date('d/m/Y H:i:s', strtotime($item->data)) }}</td>
                    <td>{{ $item->tipo == 'S' ? number_format($item->peso, 2, ',', '.') : 'REFORÇO' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </td>
          <td width="50%" valign="top" align="center">

            <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
              <thead>
                <tr>
                  <th style="width:50%;">Data</th>
                  <th style="width:50%;">Peso (g)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($itens[1] as $item)
                  <tr class="{{ $item->tipo == 'R' ? ' bg-yellow-100' : '' }}">
                    <td>{{ date('d/m/Y H:i:s', strtotime($item->data)) }}</td>
                    <td>{{ $item->tipo == 'S' ? number_format($item->peso, 2, ',', '.') : 'REFORÇO' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </td>
        </tr>
      </tbody>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
