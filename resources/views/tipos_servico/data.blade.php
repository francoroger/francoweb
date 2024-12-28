<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th style="width:60%;">Descrição</th>
      <th style="width:20%;">Parâmetro</th>
      <th class="text-center" style="width:20%;">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($tiposServico as $tipoServico)
      <tr>
        <td class="row-descricao">{{ $tipoServico->descricao }}</td>
        <td class="row-parametro">{{ $tipoServico->param }}</td>
        <td class="text-center" data-id="{{ $tipoServico->id }}" data-parametro="{{ $tipoServico->parametro }}">
          <div class="text-nowrap">
            <button class="btn btn-sm btn-icon btn-flat btn-primary action-edit" title="Editar"><i class="icon wb-pencil"></i></button>
            <button class="btn btn-sm btn-icon btn-flat btn-danger action-delete" title="Excluir"><i class="icon wb-trash"></i></button>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
