@foreach ($dados as $item)
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
