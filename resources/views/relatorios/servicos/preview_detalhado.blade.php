<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Código</th>
        <th>Data</th>
        <th>Cliente</th>
        <th>Guia</th>
        <th>Tipo Serviço</th>
        <th>Material</th>
        <th>Cor</th>
        <th class="text-right">Ml</th>
        <th class="text-right">Valor</th>
        <th class="text-right">Comissão</th>
        <th class="text-right text-nowrap">Peso (g)</th>
        <th class="text-right text-nowrap">Consumo (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens as $item)
        <tr>
          <td class="text-nowrap">{{ $item->servico->id }}</td>
          <td class="text-nowrap">{{ date('d/m/Y', strtotime($item->servico->datavenda)) }}</td>
          <td class="text-nowrap">{{ $item->servico->cliente->identificacao ?? '' }}</td>
          <td class="text-nowrap">{{ $item->servico->guia->nome ?? '' }}</td>
          <td class="text-nowrap">{{ $item->tipo_servico->descricao ?? '' }}</td>
          <td class="text-nowrap">{{ $item->material->descricao ?? '' }}</td>
          <td class="text-nowrap">{{ $item->cor->descricao ?? '' }}</td>
          <td class="text-nowrap text-right">{{ $item->milesimos }}</td>
          <td class="text-nowrap text-right">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
          <td class="text-nowrap text-right">R$ {{ number_format($item->valor_comis, 2, ',', '.') }}</td>
          <td class="text-nowrap text-right">{{ number_format($item->peso, 0, ',', '.') }}</td>
          <td class="text-nowrap text-right">{{ number_format($item->consumo, 2, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td colspan="8">Totais:</td>
        <td class="text-nowrap text-right">R$ {{ number_format($total['valor'], 2, ',', '.') }}</td>
        <td class="text-nowrap text-right">R$ {{ number_format($total['valor_comis'], 2, ',', '.') }}</td>
        <td class="text-nowrap text-right">{{ number_format($total['peso'], 0, ',', '.') }} g</td>
        <td class="text-nowrap text-right">{{ number_format($total['consumo'], 2, ',', '.') }} g</td>
        <td></td>
      </tr>
    </tfoot>
  </table>
</div>

{{ $itens->links() }}
