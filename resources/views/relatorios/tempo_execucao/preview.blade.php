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
      @foreach ($servicos as $servico)
      @php
        $freceb = $servico->recebimentos->sortBy('data_hora_entrada')->first() ?? null;
        $catalog = $servico->catalogacao ?? null;
        $primeira = null;
        $ultima = null;
      @endphp
        <tr>
          <td rowspan="2">{{ $servico->cliente->nome ?? '' }}</td>
          <td>
            @if ($freceb)
              @if ($freceb->carbon_data_hora_entrada)
                <span class="text-nowrap">de {{ $freceb->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                @php
                  if (!$primeira) {
                    $primeira = $freceb->carbon_data_hora_entrada;
                  }
                  $ultima = $freceb->carbon_data_hora_entrada;
                @endphp
              @endif
              @if ($servico->carbon_data_hora_entrada)
                <span class="text-nowrap">até {{ $servico->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span>
                @php
                  if (!$primeira) {
                    $primeira = $servico->carbon_data_hora_entrada;
                  }
                  $ultima = $servico->carbon_data_hora_entrada;
                @endphp
              @endif
            @endif
          </td>
          <td>
            @if ($servico->carbon_data_hora_entrada->format('d/m/Y H:i'))
              <span class="text-nowrap">de {{ $servico->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
              @php
                if (!$primeira) {
                  $primeira = $servico->carbon_data_hora_entrada;
                }
                $ultima = $servico->carbon_data_hora_entrada;
              @endphp
            @endif
            @if ($catalog)
              @if ($catalog->carbon_data_hora_entrada)
                <span class="text-nowrap">até {{ $catalog->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span>
                @php
                  if (!$primeira) {
                    $primeira = $catalog->carbon_data_hora_entrada;
                  }
                  $ultima = $catalog->carbon_data_hora_entrada;
                @endphp
              @endif
            @endif
          </td>
          <td>
            @if ($catalog)
              @php
                $catalog_ini = $catalog->historico->where('status', 'A')->first();
                $catalog_fim = $catalog->historico->where('status', 'A')->where('data_fim', '<>', null)->first();
              @endphp
              @if ($catalog_ini)
                @if ($catalog_ini->carbon_data_hora_entrada)
                  <span class="text-nowrap">de {{ $catalog_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                  @php
                    if (!$primeira) {
                      $primeira = $catalog_ini->carbon_data_hora_entrada;
                    }
                    $ultima = $catalog_ini->carbon_data_hora_entrada;
                  @endphp
                @endif
              @endif
              @if ($catalog_fim)
                @if ($catalog_fim->carbon_data_hora_saida)
                  <span class="text-nowrap">até {{ $catalog_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                  @php
                    if (!$primeira) {
                      $primeira = $catalog_fim->carbon_data_hora_saida;
                    }
                    $ultima = $catalog_fim->carbon_data_hora_saida;
                  @endphp
                @endif
              @endif
            @endif
          </td>
          <td>
            @if ($catalog)
              @php
                $banho_ini = $catalog->historico->where('status', 'F')->first();
                $banho_fim = $catalog->historico->where('status', 'F')->where('data_fim', '<>', null)->first();
              @endphp
              @if ($banho_ini)
                @if ($banho_ini->carbon_data_hora_entrada)
                  <span class="text-nowrap">de {{ $banho_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                  @php
                    if (!$primeira) {
                      $primeira = $banho_ini->carbon_data_hora_entrada;
                    }
                    $ultima = $banho_ini->carbon_data_hora_entrada;
                  @endphp
                @endif
              @endif
              @if ($banho_fim)
                @if ($banho_fim->carbon_data_hora_saida)
                  <span class="text-nowrap">até {{ $banho_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                  @php
                    if (!$primeira) {
                      $primeira = $banho_fim->carbon_data_hora_saida;
                    }
                    $ultima = $banho_fim->carbon_data_hora_saida;
                  @endphp
                @endif
              @endif
            @endif
          </td>
          <td>
            @if ($catalog)
              @php
                $rev_ini = $catalog->historico->where('status', 'G')->first();
                $rev_fim = $catalog->historico->where('status', 'G')->where('data_fim', '<>', null)->first();
              @endphp
              @if ($rev_ini)
                @if ($rev_ini->carbon_data_hora_entrada)
                  <span class="text-nowrap">de {{ $rev_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                  @php
                    if (!$primeira) {
                      $primeira = $rev_ini->carbon_data_hora_entrada;
                    }
                    $ultima = $rev_ini->carbon_data_hora_entrada;
                  @endphp
                @endif
              @endif
              @if ($rev_fim)
                @if ($rev_fim->carbon_data_hora_saida)
                  <span class="text-nowrap">até {{ $rev_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                  @php
                    if (!$primeira) {
                      $primeira = $rev_fim->carbon_data_hora_saida;
                    }
                    $ultima = $rev_fim->carbon_data_hora_saida;
                  @endphp
                @endif
              @endif
            @endif
          </td>
          <td>
            @if ($catalog)
              @php
                $exped_ini = $catalog->historico->where('status', 'C')->first();
                $exped_fim = $catalog->historico->where('status', 'C')->where('data_fim', '<>', null)->first();
              @endphp
              @if ($exped_ini)
                @if ($exped_ini->carbon_data_hora_entrada)
                  <span class="text-nowrap">de {{ $exped_ini->carbon_data_hora_entrada->format('d/m/Y H:i') }}</span><br>
                  @php
                    if (!$primeira) {
                      $primeira = $exped_ini->carbon_data_hora_entrada;
                    }
                    $ultima = $exped_ini->carbon_data_hora_entrada;
                  @endphp
                @endif
              @endif
              @if ($exped_fim)
                @if ($exped_fim->carbon_data_hora_saida)
                  <span class="text-nowrap">até {{ $exped_fim->carbon_data_hora_saida->format('d/m/Y H:i') }}</span>
                  @php
                    if (!$primeira) {
                      $primeira = $exped_fim->carbon_data_hora_saida;
                    }
                    $ultima = $exped_fim->carbon_data_hora_saida;
                  @endphp
                @endif
              @endif
            @endif
          </td>
          <td>
            @if ($primeira)
              <span class="text-nowrap">de {{ $primeira->format('d/m/Y H:i') }}</span><br>
            @endif
            @if ($ultima)
            <span class="text-nowrap">até {{ $ultima->format('d/m/Y H:i') }}</span><br>
            @endif
          </td>
        </tr>
        <tr>
          <td>
            {{ $freceb ? str_replace('depois', '', str_replace('antes', '', $freceb->carbon_data_hora_entrada->diffForHumans($servico->carbon_data_hora_entrada))) : '' }}
          </td>
          <td>
            {{ $catalog ? str_replace('depois', '', str_replace('antes', '', $servico->carbon_data_hora_entrada->diffForHumans($catalog->carbon_data_hora_entrada))) : '' }}
          </td>
          <td>
            @if ($catalog_ini && $catalog_fim)
              {{ str_replace('depois', '', str_replace('antes', '', $catalog_ini->carbon_data_hora_entrada->diffForHumans($catalog_fim->carbon_data_hora_saida))) }}
            @endif
          </td>
          <td>
            @if ($banho_ini && $banho_fim)
              {{ str_replace('depois', '', str_replace('antes', '', $banho_ini->carbon_data_hora_entrada->diffForHumans($banho_fim->carbon_data_hora_saida))) }}
            @endif
          </td>
          <td>
            @if ($rev_ini && $rev_fim)
              {{ str_replace('depois', '', str_replace('antes', '', $rev_ini->carbon_data_hora_entrada->diffForHumans($rev_fim->carbon_data_hora_saida))) }}
            @endif
          </td>
          <td>
            @if ($exped_ini && $exped_fim)
              {{ str_replace('depois', '', str_replace('antes', '', $exped_ini->carbon_data_hora_entrada->diffForHumans($exped_fim->carbon_data_hora_saida))) }}
            @endif
          </td>
          <td>
            @if ($primeira && $ultima)
              <strong>{{ $primeira->diffinHours($ultima) }} horas</strong>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{ $servicos->links() }}
