<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Código</th>
        <th>Data Início</th>
        <th>Data Fim</th>
        <th>Cliente</th>
        <th>Status</th>
        <th class="text-right">Itens</th>
        <th class="text-right">Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($retrabalhos as $retrabalho)
        <tr>
          <td class="text-nowrap">{{ $retrabalho->id }}</td>
          <td class="text-nowrap">
            {{ $retrabalho->data_inicio ? date('d/m/Y', strtotime($retrabalho->data_inicio)) : '' }}</td>
          <td class="text-nowrap">{{ $retrabalho->data_fim ? date('d/m/Y', strtotime($retrabalho->data_fim)) : '' }}
          </td>
          <td class="text-nowrap">{{ $retrabalho->cliente->identificacao ?? '' }}</td>
          <td>
            @switch($retrabalho->status)
              @case('G') Aguardando @break
              @case('A') Em Andamento @break
              @case('E') Concluído @break
              @default Aguardando
            @endswitch
          </td>
          <td class="text-nowrap text-right">{{ $retrabalho->itens->count() }}</td>
          <td class="text-nowrap text-right">{{ number_format($retrabalho->itens->sum('peso'), 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td colspan="5">Totais:</td>
        <td class="text-nowrap text-right">{{ number_format($total['itens'], 0, ',', '.') }}</td>
        <td class="text-nowrap text-right">{{ number_format($total['peso'], 0, ',', '.') }} g</td>
      </tr>
    </tfoot>
  </table>
</div>

{{ $retrabalhos->links() }}
