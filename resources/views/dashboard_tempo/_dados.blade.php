<table class="table">
  <thead>
    <tr>
      <th>Cliente</th>
      <th>Início</th>
      <th>Término</th>
      <th>Tempo de Execução</th>
      <th>Recebimento</th>
      <th>Separação</th>
      <th>Catalogação</th>
      <th>Preparação</th>
      <th>Banho</th>
      <th>Revisão</th>
      <th>Expedição</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($servicos as $separacao)
      <tr>
        <td>{{ $separacao->cliente->identificacao }}</td>
        <td>{{ $separacao->primeira_data->format('d/m/Y H:i:s') }}</td>
        <td>{{ $separacao->ultima_data->format('d/m/Y H:i:s') }}</td>
        <td>{{ ceil($separacao->total_seconds_tempo_execucao / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_recebimento / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_separacao / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_catalogacao / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_preparacao / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_banho / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_revisao / 3600) }}</td>
        <td>{{ ceil($separacao->seconds_between_expedicao / 3600) }}</td>
      </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('total_seconds_tempo_execucao') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_recebimento') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_separacao') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_catalogacao') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_preparacao') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_banho') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_revisao') / 3600 / $servicos->count()) : 0 }}
      </td>
      <td>
        {{ $servicos->count() > 0 ? ceil($servicos->sum('seconds_between_expedicao') / 3600 / $servicos->count()) : 0 }}
      </td>
    </tr>
  </tfoot>
</table>
