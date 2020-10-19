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
      .bg-red-100 {
        background-color: #ffdbdc!important;
      }
      .strike {
        text-decoration: line-through;
        color: #AA0000;
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
    <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <thead>
        <tr>
          <th style="width:15%;">Data</th>
          <th style="width:15%;">Tipo</th>
          <th style="width:15%;">Peso Peça</th>
          <th style="width:15%;">Peso Antes</th>
          <th style="width:15%;">Peso Consumido</th>
          <th style="width:15%;">Peso Depois</th>
        </tr>
      </thead>
      <tbody>
        @if ($itens->count())
          @foreach ($itens as $item)
            <tr class="{{ $item->deleted_at ? ' strike' : '' }} {{ ($item->tipo == 'R') || ($item->tipo == 'A') ? ' bg-yellow-100' : '' }} {{ $item->excedente ? ' bg-red-100' : '' }}">
              <td>{{ date('d/m/Y H:i:s', strtotime($item->data)) }}</td>
              <td>
                @switch($item->tipo)
                  @case('R')
                    REFORÇO
                    @break
                  @case('A')
                    REFORÇO POR ANÁLISE
                    @break
                  @default
                    @if ($item->excedente)
                    EXCEDENTE
                    @else
                    PASSAGEM
                    @endif
                @endswitch
              </td>
              <td>{{ $item->peso_peca ? number_format($item->peso_peca, 0, ',', '.') . ' g' : '' }}</td>
              <td>{{ $item->peso_antes ? number_format($item->peso_antes, 0, ',', '.') . ' g' : '' }}</td>
              <td>
                @if ($item->tipo == 'S')
                  {{ number_format($item->peso, 0, ',', '.') }} g
                @else
                  {{ $item->tipo == 'A' ? 'REFORÇO POR ANÁLISE' : 'REFORÇO' }}  
                @endif
              </td>
              <td>{{ $item->peso_depois ? number_format($item->peso_depois, 0, ',', '.') . ' g' : '' }}</td>
            </tr>
            @if ($item->tipo == 'A' && $item->motivo)
              <tr class="bg-yellow-100">
                <td colspan="6"><b>Motivo:</b> {{ $item->motivo }}</td>
              </tr>
            @endif
          @endforeach
        @endif
      </tbody>
    </table>
    <!-- Fim Conteúdo -->

  </body>
</html>
