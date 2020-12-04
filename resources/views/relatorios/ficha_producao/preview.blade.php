<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th style="width:15%;">Data / Hora</th>
        <th style="width:15%;">Tipo</th>
        <th style="width:10%;">Peso Peça</th>
        <th style="width:15%;">Ponteiro Antes</th>
        <th style="width:20%;">Peso Consumido</th>
        <th style="width:15%;">Ponteiro Depois</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itens as $item)
        <tr class="{{ $item->deleted_at ? ' text-strike text-danger' : '' }} {{ ($item->tipo == 'R') || ($item->tipo == 'A') ? ' bg-yellow-100' : '' }} {{ $item->excedente ? ' bg-red-100' : '' }}">
          <td>{{ date('d/m/Y H:i:s', strtotime($item->data)) }}</td>
          <td>
            @switch($item->tipo)
              @case('R')
                REFORÇO
                @break
              @case('A')
                REFORÇO POR ANÁLISE
                @break
              @default
                @if ($item->excedente)
                EXCEDENTE
                @else
                PASSAGEM
                @endif
            @endswitch
          </td>
          <td>{{ $item->peso_peca ? number_format($item->peso_peca, 0, ',', '.') . ' g' : '' }}</td>
          <td>{{ $item->peso_antes ? number_format($item->peso_antes, 0, ',', '.') . ' g' : '' }}</td>
          <td>
            @if ($item->tipo == 'S')
              {{ number_format($item->peso, 0, ',', '.') }} g
              @if ($item->formula && !$item->excedente)
                <br>
                <small>{{ $item->formula }}</small>
              @endif
            @else
              {{ number_format($item->peso, 0, ',', '.') }} g
            @endif
          </td>
          <td>{{ $item->peso_depois ? number_format($item->peso_depois, 0, ',', '.') . ' g' : '' }}</td>
        </tr>
        @if ($item->tipo == 'A' && $item->motivo)
          <tr class="bg-yellow-100{{ $item->deleted_at ? ' text-strike text-danger' : '' }}">
            <td colspan="6"><b>Motivo:</b> {{ $item->motivo }}</td>
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>

{{ $itens->links() }}
