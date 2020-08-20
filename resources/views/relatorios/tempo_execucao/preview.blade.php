<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th style="min-width: 200px !important;">Cliente</th>
        <th>Recebimento</th>
        <th>Separação</th>
        <th>Catalogação</th>
        <th>Banho</th>
        <th>Revisão</th>
        <th>Expedição</th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($servicos as $separacao)
        @php
          $primeiro = null;
          $ultimo = null;
          //Recebimento
          if ($separacao->recebimentos->sortBy('data_receb')->first()) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->first()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->first()->hora_receb) : '';
          }
          if ($separacao->recebimentos->sortBy('data_receb')->last()) {
            $ultimo = \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->last()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->last()->hora_receb);
          }
          //Separação
          $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->created_at) : '';
          $ultimo = \Carbon\Carbon::parse($separacao->created_at);
          //Catalogação
          if ($separacao->data_inicio_catalogacao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_catalogacao) : '';
          }
          if ($separacao->data_fim_catalogacao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_catalogacao);
          }
          //Preparação
          if ($separacao->data_inicio_preparacao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_preparacao) : '';
          }
          if ($separacao->data_fim_preparacao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_preparacao);
          }
          //Revisão
          if ($separacao->data_inicio_revisao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_revisao) : '';
          }
          if ($separacao->data_fim_revisao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_revisao);
          }
          //Expedição
          if ($separacao->data_inicio_expedicao) {
            $primeiro == null ? $primeiro = \Carbon\Carbon::parse($separacao->data_inicio_expedicao) : '';
          }
          if ($separacao->data_fim_expedicao) {
            $ultimo = \Carbon\Carbon::parse($separacao->data_fim_expedicao);
          }
        @endphp
        <tr>
          <td rowspan="2">{{ $separacao->cliente->identificacao ?? '' }}</td>
          <td>{{ $separacao->recebimentos->sortBy('data_receb')->first() ? \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->first()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->first()->hora_receb)->format('d/m/Y H:i:s') : ''  }}</td>
          <td>{{ \Carbon\Carbon::parse($separacao->created_at)->format('d/m/Y H:i:s') }}</td>
          <td>{{ $separacao->data_inicio_catalogacao ? \Carbon\Carbon::parse($separacao->data_inicio_catalogacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_inicio_preparacao ? \Carbon\Carbon::parse($separacao->data_inicio_preparacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_inicio_revisao ? \Carbon\Carbon::parse($separacao->data_inicio_revisao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_inicio_expedicao ? \Carbon\Carbon::parse($separacao->data_inicio_expedicao)->format('d/m/Y H:i:s') : '' }}</td>
          <td rowspan="2">
            {{ $primeiro->diffForHumans($ultimo, \Carbon\CarbonInterface::DIFF_ABSOLUTE, false, 4) }}
          </td>
        </tr>
        <tr>
          <td>{{ $separacao->recebimentos->sortBy('data_receb')->last() ? \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->last()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->last()->hora_receb)->format('d/m/Y H:i:s') : ''  }}</td>
          <td>{{ $separacao->data_inicio_catalogacao ? \Carbon\Carbon::parse($separacao->data_inicio_catalogacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_catalogacao ? \Carbon\Carbon::parse($separacao->data_fim_catalogacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_preparacao ? \Carbon\Carbon::parse($separacao->data_fim_preparacao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_revisao ? \Carbon\Carbon::parse($separacao->data_fim_revisao)->format('d/m/Y H:i:s') : '' }}</td>
          <td>{{ $separacao->data_fim_expedicao ? \Carbon\Carbon::parse($separacao->data_fim_expedicao)->format('d/m/Y H:i:s') : '' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $servicos->links() }}
