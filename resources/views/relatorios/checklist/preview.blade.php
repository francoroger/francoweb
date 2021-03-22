<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Código</th>
        <th>Data</th>
        <th>Cliente</th>
        <th>Status</th>
        <th>Itens</th>
        <th>Checados</th>
        <th>Conforme</th>
        <th>Não Conforme</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($catalogacoes as $catalogacao)
        <tr>
          <td class="text-nowrap"><a href="{{ route('catalogacao_checklist.show', $catalogacao->id) }}" target="_blank">{{ $catalogacao->id }}</a></td>
          <td class="text-nowrap">{{ date('d/m/Y H:i', strtotime($catalogacao->datacad . ' ' . $catalogacao->horacad)) }}</td>
          <td class="text-nowrap">{{ $catalogacao->cliente->identificacao ?? '' }}</td>
          <td class="text-nowrap">
            @switch($catalogacao->status)
              @case('F') Banho/Preparação @break
              @case('G') Aguardando @break
              @case('P') Em Andamento @break
              @case('C') Concluída @break
              @case('L') Concluída @break
              @default 
            @endswitch
          </td>
          <td>{{ $catalogacao->itens->count() }}</td>
          <td>{{ $catalogacao->itens->where('status_check')->count() }}</td>
          <td><a href="{{ route('catalogacao_checklist.show', ['id' => $catalogacao->id, 'status' => 'S']) }}" target="_blank">{{ $catalogacao->itens->where('status_check', 'S')->count() }}</a></td>
          <td><a href="{{ route('catalogacao_checklist.show', ['id' => $catalogacao->id, 'status' => 'N']) }}" target="_blank">{{ $catalogacao->itens->where('status_check', 'N')->count() }}</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $catalogacoes->links() }}
