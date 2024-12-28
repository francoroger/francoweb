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
          <th style="width:40%;">Grupo</th>
          <th style="width:15%;">Valor</th>
          <th style="width:15%;">Comissão</th>
          <th style="width:15%;">Peso (g)</th>
          <th style="width:15%;">Cons.(g)</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($itens->sortBy(function ($group, $key) {
          return $key;
        }) as $k => $v)
          @include('relatorios.servicos._destrincha_resumido', [
            'total_niveis' => $total_grupos,
            'nivel_atual' => 1,
            'nome_grupo' => $k,
            'dados' => $v
          ])
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td align="right">Total Geral:</td>
          <td>R$ {{ number_format($total['valor'], 2, ',', '.') }}</td>
          <td>R$ {{ number_format($total['valor_comis'], 2, ',', '.') }}</td>
          <td>{{ number_format($total['peso'], 0, ',', '.') }} g</td>
          <td>{{ number_format($total['consumo'], 2, ',', '.') }} g</td>
        </tr>
      </tfoot>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
