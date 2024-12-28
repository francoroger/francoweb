{{-- 
  $total_niveis
  $nivel_atual
  $nome_grupo
  $dados
--}}

@if ($nivel_atual < $total_niveis)
  <tr>
    <td class="bg-blue-grey-200 grupo" colspan="12">{{ $nome_grupo }}</td>
  </tr>  

  @php($nivel_atual++)
  
  @foreach ($dados->sortBy(function ($group, $key) {
    return $key;
  }) as $k => $v)
    @include('relatorios.servicos._destrincha', [
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

  @include('relatorios.servicos._linha', [
    'dados' => $v
  ])

<tr class="font-weight-500">
  <td colspan="8" align="right">Totais:</td>
  <td class="text-nowrap text-right">R$ {{ number_format($v->sum('valor'), 2, ',', '.') }}</td>
  <td class="text-nowrap text-right">R$ {{ number_format($v->sum('valor_comis'), 2, ',', '.') }}</td>
  <td class="text-nowrap text-right">{{ number_format($v->sum('peso'), 0, ',', '.') }} g</td>
  <td class="text-nowrap text-right">{{ number_format($v->sum('consumo'), 2, ',', '.') }} g</td>
</tr>

  
@endif
