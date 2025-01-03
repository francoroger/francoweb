{{-- $total_niveis
  $nivel_atual
  $nome_grupo
  $dados --}}

@if ($nivel_atual < $total_niveis)
  <tr>
    <td class="bg-blue-grey-200 grupo" colspan="12">{{ $nome_grupo }}</td>
  </tr>

  @php($nivel_atual++)

  @foreach ($dados->sortBy(function ($group, $key) {
        return $key;
    })
    as $k => $v)
    @include('relatorios.retrabalho._destrincha', [
    'total_niveis' => $total_niveis,
    'nivel_atual' => $nivel_atual,
    'nome_grupo' => $k,
    'dados' => $v
    ])
  @endforeach

@else
  <tr>
    <td class="bg-blue-grey-200 grupo" colspan="12">{{ $nome_grupo }}</td>
  </tr>

  @include('relatorios.retrabalho._linha', [
  'dados' => $v
  ])

  <tr class="font-weight-500">
    <td colspan="8">Total</td>
    <td class="text-nowrap text-right">{{ number_format($v->sum('peso'), 0, ',', '.') }} g</td>
  </tr>

@endif
