<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th style="width:10%;">Data</th>
      <th style="width:40%;">Cliente</th>
      <th style="width:10%;">Tipo Serviço</th>
      <th style="width:10%;">Material</th>
      <th style="width:10%;">Cor</th>
      <th style="width:5%;">Ml.</th>
      <th style="width:10%;">Peso</th>
      <th class="text-center" style="width:5%;">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($passagens as $passagem)
      <tr>
        <td>{{ date('d/m/Y', strtotime($passagem->data_servico)) }}</td>
        <td>
          {{ $passagem->cliente->nome ?? '' }} <br>
          <small>{{ $passagem->cliente->rzsc ?? '' }}</small>
        </td>
        <td>{{ $passagem->tipo_servico->descricao ?? '' }}</td>
        <td>{{ $passagem->material->descricao ?? '' }}</td>
        <td>{{ $passagem->cor->descricao ?? '' }}</td>
        <td>{{ $passagem->milesimos }}</td>
        <td>{{ number_format($passagem->peso, 0, ',', '.') }} g</td>
        <td class="text-center" data-id="{{ $passagem->id }}">
          <div class="text-nowrap">
            <button class="btn btn-sm btn-icon btn-flat btn-danger action-delete" title="Excluir"><i class="icon wb-trash"></i></button>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<nav>
  {{ $passagens->links() }}
</nav>
