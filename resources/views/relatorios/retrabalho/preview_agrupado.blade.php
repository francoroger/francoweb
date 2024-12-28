<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Código</th>
        <th>Data Início</th>
        <th>Data Fim</th>
        <th>Cliente</th>
        <th>Status</th>
        <th>Tipo de Falha</th>
        <th>Serviço</th>
        <th>Material</th>
        <th class="text-nowrap text-right">Peso (g)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens as $k => $v)
        @include('relatorios.retrabalho._destrincha', [
        'total_niveis' => $total_grupos,
        'nivel_atual' => 1,
        'nome_grupo' => $k,
        'dados' => $v
        ])
      @endforeach
    </tbody>
    <tfoot>
      <tr class="font-weight-500">
        <td colspan="8">Total Geral:</td>
        <td class="text-nowrap text-right">{{ number_format($total['peso'], 0, ',', '.') }} g</td>
      </tr>
    </tfoot>
  </table>
</div>
