<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Data</th>
        <th>Cliente</th>
        <th>Tipo Serviço</th>
        <th>Material</th>
        <th>Cor</th>
        <th>Camada</th>
        <th>Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens as $item)
        <tr>
          <td class="text-nowrap">{{ $item->data_servico }}</td>
          <td class="text-nowrap">{{ $item->cliente }}</td>
          <td class="text-nowrap">{{ $item->tipo_servico }}</td>
          <td class="text-nowrap">{{ $item->material }}</td>
          <td class="text-nowrap">{{ $item->cor }}</td>
          <td class="text-nowrap">{{ $item->milesimos }}</td>
          <td class="text-nowrap">{{ $item->peso }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $itens->links() }}
