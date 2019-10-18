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
        <th>Ml</th>
        <th>Valor</th>
        <th>Peso (g)</th>
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
          <td class="text-nowrap">{{ $item->milesimos }}</td>
          <td class="text-nowrap">R$ {{ $item->valor }}</td>
          <td class="text-nowrap">{{ $item->peso }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $itens->links() }}
