<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN""http://www.w3.org/TR/REC-html40/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
  <title>Catalogação</title>

  <style type="text/css">
    body * {
      font-family: Arial, sans-serif !important;
    }

    body table[class=mobile-fl] {
      width: 100% !important;
      margin-left: 0px;
      margin-right: 0px;
      clear: both;
      table-layout: fixed;
    }

    body table[class=mobile-fr] {
      width: 100% !important;
      margin-left: 0px;
      margin-right: 0px;
      clear: both;
      table-layout: fixed;
    }

    .pad-l {
      padding-left: 10px;
    }

    .pad-r {
      padding-right: 10px;
    }

    address {
      font-size: 12px;
    }

    footer {
      position: fixed;
      bottom: -20px;
      left: 0px;
      right: 0px;
      height: 20px;
      font-size: 10px;
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

    .img-responsive-w {
      height: auto !important;
      max-width: none !important;
      width: 100% !important;
    }

    .img-responsive-h {
      height: 170px !important;
      max-height: none !important;
      width: auto !important;
    }

    .valor-desconto {
      color: #dc3545 !important;
      font-weight: 900;
    }

    .valor-original {
      color: #37474f;
      text-decoration: line-through;
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
          <td style="text-align:right;">

          </td>
        </tr>
      </tbody>
    </table>
  </footer>

  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody>
      <tr>
        <td width="100%" valign="top" align="center">
          <!-- Cabeçalho -->
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr>
                <td width="50%" valign="top" align="left">
                  <img src="{{ public_path('assets/logo/logo.jpg') }}" width="200px" alt="">
                </td>
                <td width="50%" valign="top" align="right">
                  <b>FRANCO GALVÂNICA</b>
                  <address>
                    R. José Mota dos Santos, 113<br>
                    Jardim Adelia Cavicchia Grotta<br>
                    Limeira - SP<br>
                    CEP: 13482-176<br>
                    Telefone: (19) 3451-6246
                  </address>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- Fim Cabeçalho -->
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="center">
          <!-- Título -->
          <h3>CATALOGAÇÃO {{ $catalogacao->id }}</h3>
          <!-- Fim Título -->
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="center">
          <!-- Dados da Catalogação -->
          <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
            <tbody>
              <tr>
                <td rowspan="2" width="80%" valign="top" align="left">
                  <b>{{ $catalogacao->cliente->identificacao ?? '' }}</b> <br>
                  @if ($catalogacao->cliente->rzsc ?? false)
                    {{ $catalogacao->cliente->rzsc }} <br>
                  @endif
                  @if ($catalogacao->cliente->endereco ?? false)
                    {{ $catalogacao->cliente->endereco }}
                    {{ $catalogacao->cliente->numero ? ', ' . $catalogacao->cliente->numero : '' }}
                    {{ $catalogacao->cliente->compl ? ' - ' . $catalogacao->cliente->compl : '' }} <br>
                  @endif
                  @if ($catalogacao->cliente->bairro ?? false)
                    {{ $catalogacao->cliente->bairro }} -
                  @endif
                  @if ($catalogacao->cliente->cidade ?? false)
                    {{ $catalogacao->cliente->cidade }}
                    {{ $catalogacao->cliente->uf ? '/ ' . $catalogacao->cliente->uf : '' }}
                  @endif
                </td>
                <td width="20%" valign="top" align="center">
                  <b>Data:</b><br>
                  {{ date('d/m/Y', strtotime($catalogacao->datacad)) }}<br>
                </td>
              </tr>
              <tr>
                <td width="20%" valign="top" align="center">
                  <b>Cotação do Ouro:</b><br>
                  R$ {{ number_format($catalogacao->valor_cotacao, 2, ',', '.') }}
                </td>
              </tr>
            </tbody>
          </table>
          <!-- Fim Dados da Catalogação -->
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Spacer -->
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>

  @foreach ($itens->chunk(2) as $chunk)
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody>
        <tr>
          <td width="100%" valign="top" align="center">
            <!-- Itens -->
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  @foreach ($chunk as $item)
                    <td class="pad-{{ $loop->odd ? 'r' : 'l' }}" width="50%" valign="top"
                      align="{{ $loop->odd ? 'left' : 'right' }}">
                      <!-- Col 1 -->
                      <table class="mobile-f{{ $loop->odd ? 'l' : 'r' }}" cellspacing="0" cellpadding="0" border="0"
                        align="center">
                        <tbody>
                          <tr>
                            <td width="100%" valign="top">
                              <!-- Card -->
                              <table class="table-bordered" width="100%" cellspacing="0" cellpadding="0" border="0"
                                align="center">
                                <tbody>
                                  <tr>
                                    <td width="100%" valign="top" colspan="2" align="center">
                                      @if (file_exists('fotos/' . $item->foto))
                                        <img class="img-responsive-h" src="{{ public_path('fotos/' . $item->foto) }}"
                                          alt="...">
                                      @else
                                        <img class="img-responsive-h"
                                          src="{{ public_path('assets/photos/placeholder.png') }}"
                                          alt="{{ public_path('fotos/' . $item->foto) }}">
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="85%" valign="top">
                                      <b>Produto:</b>
                                      {{ $item->produto->descricao ?? '' }} {{ $item->material->descricao ?? '' }}
                                      {{ $item->milesimos ? "$item->milesimos mil." : '' }}
                                    </td>
                                    <td width="15%" valign="top">
                                      <b>Peso:</b> {{ number_format($item->peso, 1, ',', '.') }} g
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="85%" valign="top">
                                      <b>Fornecedor:</b> {{ $item->fornecedor->nome ?? '' }}
                                    </td>
                                    <td width="15%" valign="top">
                                      <b>Banho:</b> R$ {{ number_format($item->custo_total, 2, ',', '.') }}
                                    </td>
                                  </tr>
                                  <tr>
                                    @if ($item->preco_bruto > 0)
                                      <td width="85%" valign="top">
                                        <b>Bruto:</b>
                                        @if ($item->desconto)
                                          <span class="valor-original">R$
                                            {{ number_format($item->preco_bruto, 2, ',', '.') }}</span>
                                          <span class="valor-desconto">R$
                                            {{ number_format($item->valor_com_desconto, 2, ',', '.') }}</span>
                                        @else
                                          R$ {{ number_format($item->preco_bruto, 2, ',', '.') }}
                                        @endif
                                      </td>
                                    @else
                                      <td width="85%" valign="top" bgcolor="#e4eaec">&nbsp;</td>
                                    @endif
                                    <td width="15%" valign="top">
                                      <b>Ref.:</b> {{ $item->referencia }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="85%" valign="top">
                                      <b>Informação:</b> {{ $item->observacoes }}
                                    </td>
                                    <td width="15%" valign="top">
                                      <b>Qtde.:</b> {{ number_format($item->quantidade, 0, ',', '.') }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="85%" valign="top">
                                      <b>Obs:</b> {{ $item->obs_check }}
                                    </td>
                                    <td width="15%" valign="top">
                                      <b>Falha:</b> {{ $item->tipo_falha->descricao ?? '' }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="85%" valign="top">
                                      @if ($item->preco_bruto > 0)
                                        <b>CUSTO TOTAL:</b>
                                      @else
                                        <b>CUSTO DO BANHO:</b>
                                      @endif
                                      R$
                                      {{ number_format($item->custo_total + $item->valor_com_desconto ?? $item->preco_bruto, 2, ',', '.') }}
                                    </td>
                                    <td width="15%" style="border-bottom:none!important; border-right:none!important;">
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <!-- Fim Card -->
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <!-- Fim Col 1 -->
                    </td>
                  @endforeach


                </tr>
              </tbody>
            </table>
            <!-- Fim Itens -->
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Spacer -->
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </tbody>
    </table>
  @endforeach

  <script type="text/php">
    if (isset($pdf)) {
              $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
              $size = 8;
              $font = $fontMetrics->getFont("Arial");
              $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
              $x = ($pdf->get_width() - $width) - 15;
              $y = $pdf->get_height() - 30;
              $pdf->page_text($x, $y, $text, $font, $size);
          }
      </script>

</body>

</html>
