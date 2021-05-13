<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover font-size-12">
    <thead>
      <tr>
        <th colspan="2" style="min-width: 200px !important;">Cliente</th>
        @if (in_array('Recebimento', $etapas))
          <th>Recebimento</th>
          <th class="text-center"><i class="icon fa-clock-o" aria-hidden="true"></i></th>
        @endif
        @if (in_array('Separação', $etapas))
          <th>Separação</th>
          <th class="text-center"><i class="icon fa-clock-o" aria-hidden="true"></i></th>
        @endif
        @if (in_array('Catalogação', $etapas))
          <th>Catalogação</th>
          <th class="text-center"><i class="icon fa-clock-o" aria-hidden="true"></i></th>
        @endif
        @if (in_array('Preparação', $etapas))
          <th>Preparação</th>
          <th class="text-center"><i class="icon fa-clock-o" aria-hidden="true"></i></th>
        @endif
        @if (in_array('Banho', $etapas))
          <th>Banho</th>
          <th class="text-center"><i class="icon fa-clock-o" aria-hidden="true"></i></th>
        @endif
        @if (in_array('Revisão', $etapas))
          <th>Revisão</th>
          <th class="text-center"><i class="icon fa-clock-o" aria-hidden="true"></i></th>
        @endif
        @if (in_array('Expedição', $etapas))
          <th>Expedição</th>
        @endif
        <th>Total Execução</th>
        <th>Total Espera</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($servicos as $separacao)
        <tr>
          <td rowspan="5">
            <strong>{{ $separacao->cliente->identificacao ?? '' }}</strong><br><br>
            <strong>Catalogação nº:</strong> {{ $separacao->catalogacao_id }} <br>
            <strong>Separação nº:</strong> {{ $separacao->id }} <br>
            <strong>Peso: </strong> {{ number_format($separacao->recebimentos->sum('pesototal'), 0, ',', '.') }} g
            <br>
            <strong>Itens: </strong> {{ $separacao->catalogacao_id ? $separacao->catalogacao->itens->count() : '' }}
          </td>
          <th>Início</th>
          @if (in_array('Recebimento', $etapas))
            <td>{{ $separacao->inicio_recebimento_formatted }}</td>
            <td rowspan="3" class="text-danger text-middle text-center">
              {{ $separacao->diff_for_humans_between_recebimento_separacao }}</td>
          @endif
          @if (in_array('Separação', $etapas))
            <td>{{ $separacao->inicio_separacao_formatted }}</td>
            <td rowspan="3" class="text-danger text-middle text-center">
              {{ $separacao->diff_for_humans_between_separacao_catalogacao }}</td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td>{{ $separacao->inicio_catalogacao_formatted }}</td>
            <td rowspan="3" class="text-danger text-middle text-center">
              {{ $separacao->diff_for_humans_between_catalogacao_preparacao }}</td>
          @endif
          @if (in_array('Preparação', $etapas))
            <td>{{ $separacao->inicio_preparacao_formatted }}</td>
            <td rowspan="3" class="text-danger text-middle text-center">
              {{ $separacao->diff_for_humans_between_preparacao_banho }}</td>
          @endif
          @if (in_array('Banho', $etapas))
            <td>{{ $separacao->inicio_banho_formatted }}</td>
            <td rowspan="3" class="text-danger text-middle text-center">
              {{ $separacao->diff_for_humans_between_banho_revisao }}</td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td>{{ $separacao->inicio_revisao_formatted }}</td>
            <td rowspan="3" class="text-danger text-middle text-center">
              {{ $separacao->diff_for_humans_between_revisao_expedicao }}</td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td>{{ $separacao->inicio_expedicao_formatted }}</td>
          @endif
          <td rowspan="3" class="text-middle">
            {{ $separacao->tempoExecucaoForHumans($etapa_ini, $etapa_fim) }}
          </td>
          <td rowspan="3" class="text-danger text-middle">
            {{ $separacao->tempoOciosoForHumans($etapa_ini, $etapa_fim) }}</td>
        </tr>
        <tr>
          <th>Fim</th>
          @if (in_array('Recebimento', $etapas))
            <td>{{ $separacao->fim_recebimento_formatted }}</td>
          @endif
          @if (in_array('Separação', $etapas))
            <td>{{ $separacao->fim_separacao_formatted }}</td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td>{{ $separacao->fim_catalogacao_formatted }}</td>
          @endif
          @if (in_array('Preparação', $etapas))
            <td>{{ $separacao->fim_preparacao_formatted }}</td>
          @endif
          @if (in_array('Banho', $etapas))
            <td>{{ $separacao->fim_banho_formatted }}</td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td>{{ $separacao->fim_revisao_formatted }}</td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td>{{ $separacao->fim_expedicao_formatted }}</td>
          @endif
        </tr>
        <tr>
          <th>Tempo Total</th>
          @if (in_array('Recebimento', $etapas))
            <td>{{ $separacao->diff_for_humans_between_recebimento }}</td>
          @endif
          @if (in_array('Separação', $etapas))
            <td>{{ $separacao->diff_for_humans_between_separacao }}</td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td>{{ $separacao->diff_for_humans_between_catalogacao }}</td>
          @endif
          @if (in_array('Preparação', $etapas))
            <td>{{ $separacao->diff_for_humans_between_preparacao }}</td>
          @endif
          @if (in_array('Banho', $etapas))
            <td>{{ $separacao->diff_for_humans_between_banho }}</td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td>{{ $separacao->diff_for_humans_between_revisao }}</td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td>{{ $separacao->diff_for_humans_between_expedicao }}</td>
          @endif
        </tr>
        <tr>
          <th>Horas Úteis</th>
          @if (in_array('Recebimento', $etapas))
            <td>{{ $separacao->business_time_between_recebimento }}</td>
            <td>{{ $separacao->business_time_between_recebimento_separacao }}</td>
          @endif
          @if (in_array('Separação', $etapas))
            <td>{{ $separacao->business_time_between_separacao }}</td>
            <td>{{ $separacao->business_time_between_separacao_catalogacao }}</td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td>{{ $separacao->business_time_between_catalogacao }}</td>
            <td>{{ $separacao->business_time_between_catalogacao_preparacao }}</td>
          @endif
          @if (in_array('Preparação', $etapas))
            <td>{{ $separacao->business_time_between_preparacao }}</td>
            <td>{{ $separacao->business_time_between_preparacao_banho }}</td>
          @endif
          @if (in_array('Banho', $etapas))
            <td>{{ $separacao->business_time_between_banho }}</td>
            <td>{{ $separacao->business_time_between_banho_revisao }}</td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td>{{ $separacao->business_time_between_revisao }}</td>
            <td>{{ $separacao->business_time_between_revisao_expedicao }}</td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td>{{ $separacao->business_time_between_expedicao }}</td>
          @endif
          <td>{{ $separacao->businessTimeTempoExecucaoForHumans($etapa_ini, $etapa_fim) }}</td>
          <td>{{ $separacao->businessTimeTempoOciosoForHumans($etapa_ini, $etapa_fim) }}</td>
        </tr>
        <tr>
          <th>Dias Úteis</th>
          @if (in_array('Recebimento', $etapas))
            <td>{{ $separacao->business_days_between_recebimento }}</td>
            <td>{{ $separacao->business_days_between_recebimento_separacao }}</td>
          @endif
          @if (in_array('Separação', $etapas))
            <td>{{ $separacao->business_days_between_separacao }}</td>
            <td>{{ $separacao->business_days_between_separacao_catalogacao }}</td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td>{{ $separacao->business_days_between_catalogacao }}</td>
            <td>{{ $separacao->business_days_between_catalogacao_preparacao }}</td>
          @endif
          @if (in_array('Preparação', $etapas))
            <td>{{ $separacao->business_days_between_preparacao }}</td>
            <td>{{ $separacao->business_days_between_preparacao_banho }}</td>
          @endif
          @if (in_array('Banho', $etapas))
            <td>{{ $separacao->business_days_between_banho }}</td>
            <td>{{ $separacao->business_days_between_banho_revisao }}</td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td>{{ $separacao->business_days_between_revisao }}</td>
            <td>{{ $separacao->business_days_between_revisao_expedicao }}</td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td>{{ $separacao->business_days_between_expedicao }}</td>
          @endif
          <td>{{ $separacao->businessDaysTempoExecucaoForHumans($etapa_ini, $etapa_fim) }}</td>
          <td>{{ $separacao->businessDaysTempoOciosoForHumans($etapa_ini, $etapa_fim) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $servicos->links() }}
