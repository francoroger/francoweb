<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Código</th>
        <th>Data</th>
        <th>Cliente</th>
        <th>Guia</th>
        <th class="text-right">Valor</th>
        <th class="text-right">Comissão</th>
        <th class="text-right">Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($servicos as $servico)
        <tr>
          <td class="text-nowrap">{{ $servico->id }}</td>
          <td class="text-nowrap">{{ date('d/m/Y', strtotime($servico->datavenda)) }}</td>
          <td class="text-nowrap">{{ $servico->cliente->identificacao ?? '' }}</td>
          <td class="text-nowrap">{{ $servico->guia->nome ?? '' }}</td>
          <td class="text-nowrap text-right">R$ {{ number_format($servico->itens->sum('valor'), 2, ',', '.') }}</td>
          <td class="text-nowrap text-right">R$ {{ number_format($servico->itens->sum('valor_comis'), 2, ',', '.') }}</td>
          <td class="text-nowrap text-right">{{ number_format($servico->itens->sum('peso'), 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td colspan="4">Totais:</td>
        <td class="text-nowrap text-right">R$ {{ number_format($total['valor'], 2, ',', '.') }}</td>
        <td class="text-nowrap text-right">R$ {{ number_format($total['valor_comis'], 2, ',', '.') }}</td>
        <td class="text-nowrap text-right">{{ number_format($total['peso'], 0, ',', '.') }} g</td>
      </tr>
    </tfoot>
  </table>
</div>

{{ $servicos->links() }}
