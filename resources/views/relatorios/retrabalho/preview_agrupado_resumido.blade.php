<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Grupo</th>
        <th class="text-right text-nowrap">Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens->sortBy(function ($group, $key) {
        return $key;
    })
    as $k => $v)
        @include('relatorios.retrabalho._destrincha_resumido', [
        'total_niveis' => $total_grupos,
        'nivel_atual' => 1,
        'nome_grupo' => $k,
        'dados' => $v
        ])
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td>Total Geral:</td>
        <td class="text-right">{{ number_format($total['peso'], 0, ',', '.') }} g</td>
      </tr>
    </tfoot>
  </table>
</div>
