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
        <th class="text-right">Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens as $item)
        <tr>
          <td class="text-nowrap">{{ $item->servico->id }}</td>
          <td class="text-nowrap">{{ date('d/m/Y', strtotime($item->servico->datavenda)) }}</td>
          <td class="text-nowrap">{{ $item->servico->cliente->nome ?? '' }}</td>
          <td class="text-nowrap">{{ $item->servico->guia->nome ?? '' }}</td>
          <td class="text-nowrap">{{ $item->tipo_servico->descricao ?? '' }}</td>
          <td class="text-nowrap">{{ $item->material->descricao ?? '' }}</td>
          <td class="text-nowrap">{{ $item->cor->descricao ?? '' }}</td>
          <td class="text-nowrap text-right">{{ $item->milesimos }}</td>
          <td class="text-nowrap text-right">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
          <td class="text-nowrap text-right">{{ number_format($item->peso, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td colspan="8">Totais:</td>
        <td class="text-nowrap text-right">R$ {{ number_format($total['valor'], 2, ',', '.') }}</td>
        <td class="text-nowrap text-right">{{ number_format($total['peso'], 1, ',', '.') }} Kg</td>
      </tr>
    </tfoot>
  </table>
</div>

{{ $itens->links() }}
