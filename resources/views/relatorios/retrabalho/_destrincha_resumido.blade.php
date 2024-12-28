{{-- $total_niveis
  $nivel_atual
  $nome_grupo
  $dados --}}

@if ($nivel_atual == 1)

  @php
    $grupos_titulo = [];
  @endphp

@endif

@if ($nivel_atual < $total_niveis)
  @php
    $grupos_titulo[] = $nome_grupo;
  @endphp


  @php($nivel_atual++)

  @foreach ($dados->sortBy(function ($group, $key) {
  return $key;
  })
  as $k => $v) @include('relatorios.retrabalho._destrincha_resumido', [
  'total_niveis' => $total_niveis,
  'nivel_atual' => $nivel_atual,
  'nome_grupo' => $k,
  'dados' => $v
  ]) @endforeach

@else
  <tr>
    <td><b>{{ implode(' ', $grupos_titulo) }} {{ $nome_grupo }}</b></td>
    <td class="text-nowrap text-right">{{ number_format($v->sum('peso'), 0, ',', '.') }} g</td>
  </tr>

@endif
