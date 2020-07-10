<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Data / Hora</th>
        <th>Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens as $item)
        <tr class="{{ (($item->tipo == 'R') || ($item->tipo == 'A')) ? ' bg-yellow-100' : '' }}">
          <td class="text-nowrap">{{ date('d/m/Y H:i:s', strtotime($item->data)) }}</td>
          <td class="text-nowrap">{{ $item->tipo == 'S' ? number_format($item->peso, 0, ',', '.') : ($item->tipo == 'A' ? 'REFORÇO POR ANÁLISE' : 'REFORÇO') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $itens->links() }}
