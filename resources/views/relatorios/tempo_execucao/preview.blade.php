<div class="table-responsive">
  <table class="table table-bordered">
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
        @php
          $primeiro = null;
          $ultimo = null;
          $data_inicio_recebimento = $separacao->recebimentos->sortBy('data_receb')->first() ? \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->first()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->first()->hora_receb) : null;
          $data_fim_recebimento = $separacao->recebimentos->sortBy('data_receb')->last() ? \Carbon\Carbon::parse($separacao->recebimentos->sortBy('data_receb')->last()->data_receb . ' ' . $separacao->recebimentos->sortBy('data_receb')->last()->hora_receb) : null;
          $data_inicio_separacao = \Carbon\Carbon::parse($separacao->created_at);
          $data_fim_separacao = $separacao->data_fim_separacao ? \Carbon\Carbon::parse($separacao->data_fim_separacao) : null; 
          $data_inicio_catalogacao = $separacao->data_inicio_catalogacao ? \Carbon\Carbon::parse($separacao->data_inicio_catalogacao) : null;
          $data_fim_catalogacao = $separacao->data_fim_catalogacao ? \Carbon\Carbon::parse($separacao->data_fim_catalogacao) : null;
          $data_inicio_preparacao = $separacao->data_inicio_preparacao ? \Carbon\Carbon::parse($separacao->data_inicio_preparacao) : null;
          $data_fim_preparacao = $separacao->data_fim_preparacao ? \Carbon\Carbon::parse($separacao->data_fim_preparacao) : null;
          $data_inicio_banho = $separacao->data_inicio_banho ? \Carbon\Carbon::parse($separacao->data_inicio_banho) : null;
          $data_fim_banho = $separacao->data_fim_banho ? \Carbon\Carbon::parse($separacao->data_fim_banho) : null;
          $data_inicio_revisao = $separacao->data_inicio_revisao ? \Carbon\Carbon::parse($separacao->data_inicio_revisao) : null;
          $data_fim_revisao = $separacao->data_fim_revisao ? \Carbon\Carbon::parse($separacao->data_fim_revisao) : null;
          $data_inicio_expedicao = $separacao->data_inicio_expedicao ? \Carbon\Carbon::parse($separacao->data_inicio_expedicao) : null;
          $data_fim_expedicao = $separacao->data_fim_expedicao ? \Carbon\Carbon::parse($separacao->data_fim_expedicao) : null;
          $total_ocioso = 0;
          $total_ocioso_util = 0;

          //Recebimento
          if (in_array('Recebimento', $etapas)) {
            if ($data_inicio_recebimento) {
              $primeiro == null ? $primeiro = $data_inicio_recebimento : null;
            }
            if ($data_fim_recebimento) {
              $ultimo = $data_fim_recebimento;
            }
          }
          //Separação
          if (in_array('Separação', $etapas)) {
            $primeiro == null ? $primeiro = $data_inicio_separacao : null;
            $ultimo = $data_inicio_separacao;
          }
          //Catalogação
          if (in_array('Catalogação', $etapas)) {
            if ($data_inicio_catalogacao) {
              $primeiro == null ? $primeiro = $data_inicio_catalogacao : null;
            }
            if ($data_fim_catalogacao) {
              $ultimo = $data_fim_catalogacao;
            }
          }
          //Preparação
          if (in_array('Banho', $etapas)) {
            if ($data_inicio_preparacao) {
              $primeiro == null ? $primeiro = $data_inicio_preparacao : null;
            }
            if ($data_fim_preparacao) {
              $ultimo = $data_fim_preparacao;
            }
          }
          //Revisão
          if (in_array('Revisão', $etapas)) {
            if ($data_inicio_revisao) {
              $primeiro == null ? $primeiro = $data_inicio_revisao : null;
            }
            if ($data_fim_revisao) {
              $ultimo = $data_fim_revisao;
            }
          }
          //Expedição
          if (in_array('Expedição', $etapas)) {
            if ($data_inicio_expedicao) {
              $primeiro == null ? $primeiro = $data_inicio_expedicao : null;
            }
            if ($data_fim_expedicao) {
              $ultimo = $data_fim_expedicao;
            }
          }
        @endphp
        <tr>
          <td class="font-size-12" rowspan="4">
            <strong>{{ $separacao->cliente->identificacao ?? '' }}</strong><br><br>
            <strong>Catalogação nº:</strong> {{ $separacao->catalogacao_id }} <br>
            <strong>Separação nº:</strong> {{ $separacao->id }}
          </td>
          <th class="font-size-12">Início</th>
          @if (in_array('Recebimento', $etapas))
            <td class="font-size-12"> {{ $data_inicio_recebimento ? $data_inicio_recebimento->format('d/m/Y H:i:s') : ''  }}</td>
            <td rowspan="3" class="font-size-12 text-danger">
              @if ($data_fim_recebimento && $data_inicio_separacao)
                {{ $data_inicio_separacao->diffForHumans($data_fim_recebimento, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
                @php($total_ocioso += $data_inicio_separacao->diffInSeconds($data_fim_recebimento))
              @elseif($data_fim_recebimento)
                {{ $data_fim_recebimento->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @endif
            </td>
          @endif
          @if (in_array('Separação', $etapas))
            <td class="font-size-12">{{ $data_inicio_separacao->format('d/m/Y H:i:s') }}</td>
            <td rowspan="3" class="font-size-12 text-danger">
              @if ($data_fim_separacao && $data_inicio_catalogacao)
                {{ $data_inicio_catalogacao->diffForHumans($data_fim_separacao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
                @php($total_ocioso += $data_inicio_catalogacao->diffInSeconds($data_fim_separacao))
              @elseif($data_fim_separacao)
                {{ $data_fim_separacao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @endif
            </td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td class="font-size-12">{{ $data_inicio_catalogacao ? $data_inicio_catalogacao->format('d/m/Y H:i:s') : '' }}</td>
            <td rowspan="3" class="font-size-12 text-danger">
              @if ($data_fim_catalogacao && $data_inicio_banho)
                {{ $data_inicio_banho->diffForHumans($data_fim_catalogacao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
                @php($total_ocioso += $data_inicio_banho->diffInSeconds($data_fim_catalogacao))
              @elseif($data_fim_catalogacao)
                {{ $data_fim_catalogacao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @endif
            </td>
          @endif
          @if (in_array('Banho', $etapas))
            <td class="font-size-12">{{ $data_inicio_preparacao ? $data_inicio_preparacao->format('d/m/Y H:i:s') : '' }}</td>
            <td rowspan="3" class="font-size-12 text-danger">
              @if ($data_fim_banho && $data_inicio_revisao)
                {{ $data_inicio_revisao->diffForHumans($data_fim_banho, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
                @php($total_ocioso += $data_inicio_revisao->diffInSeconds($data_fim_banho))
              @elseif($data_fim_banho)
                {{ $data_fim_banho->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @endif
            </td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td class="font-size-12">{{ $data_inicio_revisao ? $data_inicio_revisao->format('d/m/Y H:i:s') : '' }}</td>
            <td rowspan="3" class="font-size-12 text-danger">
              @if ($data_fim_revisao && $data_inicio_expedicao)
                {{ $data_inicio_expedicao->diffForHumans($data_fim_revisao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
                @php($total_ocioso += $data_inicio_expedicao->diffInSeconds($data_fim_revisao))
              @elseif($data_fim_revisao)
                {{ $data_fim_revisao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @endif
            </td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td class="font-size-12">{{ $data_inicio_expedicao ? $data_inicio_expedicao->format('d/m/Y H:i:s') : '' }}</td>
          @endif
          <td class="font-size-12" rowspan="3">{{ $primeiro->diffForHumans($ultimo, \Carbon\CarbonInterface::DIFF_ABSOLUTE, false, 4) }}</td>
          <td class="font-size-12 text-danger" rowspan="3">
            {{ \Carbon\CarbonInterval::seconds($total_ocioso)->cascade()->forHumans() }}
          </td>
        </tr>
        <tr>
          <th class="font-size-12">Fim</th>
          @if (in_array('Recebimento', $etapas))
            <td class="font-size-12">{{ $data_fim_recebimento ? $data_fim_recebimento->format('d/m/Y H:i:s') : ''  }}</td>
          @endif
          @if (in_array('Separação', $etapas))
            <td class="font-size-12">{{ $data_fim_separacao ? $data_fim_separacao->format('d/m/Y H:i:s') : '' }}</td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td class="font-size-12">{{ $data_fim_catalogacao ? $data_fim_catalogacao->format('d/m/Y H:i:s') : '' }}</td>
          @endif
          @if (in_array('Banho', $etapas))
            <td class="font-size-12">{{ $data_fim_preparacao ? $data_fim_preparacao->format('d/m/Y H:i:s') : '' }}</td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td class="font-size-12">{{ $data_fim_revisao ? $data_fim_revisao->format('d/m/Y H:i:s') : '' }}</td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td class="font-size-12">{{ $data_fim_expedicao ? $data_fim_expedicao->format('d/m/Y H:i:s') : '' }}</td>
          @endif
        </tr>
        <tr>
          <th class="font-size-12">Tempo Total</th>
          @if (in_array('Recebimento', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_recebimento && $data_fim_recebimento)
              {{ $data_inicio_recebimento->diffForHumans($data_fim_recebimento, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @elseif($data_inicio_recebimento)
              <span class="text-info">
                {{ $data_inicio_recebimento->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              </span>
              @endif
            </td>
          @endif
          @if (in_array('Separação', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_separacao && $data_fim_separacao)
              {{ $data_inicio_separacao->diffForHumans($data_fim_separacao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @elseif($data_inicio_separacao)
              <span class="text-info">
                {{ $data_inicio_separacao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              </span>
              @endif
            </td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_catalogacao && $data_fim_catalogacao)
              {{ $data_inicio_catalogacao->diffForHumans($data_fim_catalogacao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @elseif($data_inicio_catalogacao)
              <span class="text-info">
                {{ $data_inicio_catalogacao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              </span>
              @endif
            </td>
          @endif
          @if (in_array('Banho', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_banho && $data_fim_banho)
              {{ $data_inicio_banho->diffForHumans($data_fim_banho, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @elseif($data_inicio_banho)
              <span class="text-info">
                {{ $data_inicio_banho->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              </span>
              @endif
            </td>
          @endif
          @if (in_array('Revisão', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_revisao && $data_fim_revisao)
              {{ $data_inicio_revisao->diffForHumans($data_fim_revisao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @elseif($data_inicio_revisao)
              <span class="text-info">
                {{ $data_inicio_revisao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              </span>
              @endif
            </td>
          @endif
          @if (in_array('Expedição', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_expedicao && $data_fim_expedicao)
              {{ $data_inicio_expedicao->diffForHumans($data_fim_expedicao, \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              @elseif($data_inicio_expedicao)
              <span class="text-info">
                {{ $data_inicio_expedicao->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) }}
              </span>
              @endif
            </td>
          @endif
        </tr>
        <tr>
          <th class="font-size-12">Tempo Útil</th>
          @if (in_array('Recebimento', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_recebimento && $data_fim_recebimento)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_recebimento, $data_fim_recebimento) }}
              @elseif($data_inicio_recebimento)
              <span class="text-info">
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_recebimento, now()) }}
              </span>
              @endif
            </td>
            <td class="font-size-12 text-danger">
              @if ($data_fim_recebimento && $data_inicio_separacao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_recebimento, $data_inicio_separacao) }}
                @php($total_ocioso_util += \App\Helpers\HorasUteis::calculaIntervalo($data_fim_recebimento, $data_inicio_separacao))
              @elseif($data_fim_recebimento)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_recebimento, now()) }}
              @endif
            </td>  
          @endif
          @if (in_array('Separação', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_separacao && $data_fim_separacao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_separacao, $data_fim_separacao) }}
              @elseif($data_inicio_separacao)
              <span class="text-info">
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_separacao, now()) }}
              </span>
              @endif
            </td>
            <td class="font-size-12 text-danger">
              @if ($data_fim_separacao && $data_inicio_catalogacao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_separacao, $data_inicio_catalogacao) }}
                @php($total_ocioso_util += \App\Helpers\HorasUteis::calculaIntervalo($data_fim_separacao, $data_inicio_catalogacao))
              @elseif($data_fim_separacao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_separacao, now()) }}
              @endif
            </td>
          @endif
          @if (in_array('Catalogação', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_catalogacao && $data_fim_catalogacao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_catalogacao, $data_fim_catalogacao) }}
              @elseif($data_inicio_catalogacao)
              <span class="text-info">
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_catalogacao, now()) }}
              </span>
              @endif
            </td>
            <td class="font-size-12 text-danger">
              @if ($data_fim_catalogacao && $data_inicio_banho)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_catalogacao, $data_inicio_banho) }}
                @php($total_ocioso_util += \App\Helpers\HorasUteis::calculaIntervalo($data_fim_catalogacao, $data_inicio_banho))
              @elseif($data_fim_catalogacao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_catalogacao, now()) }}
              @endif
            </td>
          @endif
          @if (in_array('Banho', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_banho && $data_fim_banho)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_banho, $data_fim_banho) }}
              @elseif($data_inicio_banho)
              <span class="text-info">
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_banho, now()) }}
              </span>
              @endif
            </td>
            <td class="font-size-12 text-danger">
              @if ($data_fim_banho && $data_inicio_revisao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_banho, $data_inicio_revisao) }}
                @php($total_ocioso_util += \App\Helpers\HorasUteis::calculaIntervalo($data_fim_banho, $data_inicio_revisao))
              @elseif($data_fim_banho)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_banho, now()) }}
              @endif
            </td>  
          @endif
          @if (in_array('Revisão', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_revisao && $data_fim_revisao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_revisao, $data_fim_revisao) }}
              @elseif($data_inicio_revisao)
              <span class="text-info">
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_revisao, now()) }}
              </span>
              @endif
            </td>
            <td class="font-size-12 text-danger">
              @if ($data_fim_revisao && $data_inicio_expedicao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_revisao, $data_inicio_expedicao) }}
                @php($total_ocioso_util += \App\Helpers\HorasUteis::calculaIntervalo($data_fim_revisao, $data_inicio_expedicao))
              @elseif($data_fim_revisao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_fim_revisao, now()) }}
              @endif
            </td>  
          @endif
          @if (in_array('Expedição', $etapas))
            <td class="font-size-12">
              @if ($data_inicio_expedicao && $data_fim_expedicao)
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_expedicao, $data_fim_expedicao) }}
              @elseif($data_inicio_expedicao)
              <span class="text-info">
                {{ \App\Helpers\HorasUteis::calculaHoras($data_inicio_expedicao, now()) }}
              </span>
              @endif
            </td>
          @endif
          <td class="font-size-12">
            {{ \App\Helpers\HorasUteis::calculaHoras($primeiro, $ultimo, false) }}
          </td>
          <td class="font-size-12 text-danger">
            {{ \Carbon\CarbonInterval::seconds($total_ocioso_util)->cascade()->forHumans() }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $servicos->links() }}
