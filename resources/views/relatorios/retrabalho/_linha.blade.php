@foreach ($dados as $item)
  <tr>
    <td>{{ $item->retrabalho->id ?? '' }}</td>
    <td>{{ $item->retrabalho->data_inicio ? date('d/m/Y', strtotime($item->retrabalho->data_inicio)) : '' }}
    </td>
    <td>{{ $item->retrabalho->data_fim ? date('d/m/Y', strtotime($item->retrabalho->data_fim)) : '' }}</td>
    <td>{{ $item->retrabalho->cliente->identificacao ?? '' }}</td>
    <td>
      @switch($item->retrabalho->status)
        @case('G') Aguardando @break
        @case('A') Em Andamento @break
        @case('E') Concluído @break
        @default Aguardando
      @endswitch
    </td>
    <td>{{ $item->tipo_falha->descricao ?? '' }}</td>
    <td>{{ $item->tipo_servico->descricao ?? '' }}</td>
    <td class="text-nowrap">{{ $item->material->descricao ?? '' }} {{ $item->cor->descricao ?? '' }}
      {{ $item->milesimos ? $item->milesimos . ' ml' : '' }}</td>
    <td class="text-nowrap text-right">{{ number_format($item->peso, 0, ',', '.') }}</td>
  </tr>
@endforeach
