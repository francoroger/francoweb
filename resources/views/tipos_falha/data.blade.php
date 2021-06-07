<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th style="width:80%;">Descrição</th>
      <th class="text-center" style="width:20%;">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($tiposFalha as $tipoFalha)
      <tr>
        <td class="row-descricao">{{ $tipoFalha->descricao }}</td>
        <td class="text-center" data-id="{{ $tipoFalha->id }}">
          <div class="text-nowrap">
            <button class="btn btn-sm btn-icon btn-flat btn-primary action-edit" title="Editar"><i
                class="icon wb-pencil"></i></button>
            <button class="btn btn-sm btn-icon btn-flat btn-danger action-delete" title="Excluir"><i
                class="icon wb-trash"></i></button>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
