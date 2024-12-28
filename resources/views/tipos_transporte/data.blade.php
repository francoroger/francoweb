<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th style="width:75%;">Descrição</th>
      <th class="text-center" style="width:25%;">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($tiposTransporte as $tipoTransporte)
      <tr>
        <td class="row-descricao">{{ $tipoTransporte->descricao }}</td>
        <td class="text-center" data-id="{{ $tipoTransporte->id }}">
          <div class="text-nowrap">
            <button class="btn btn-sm btn-icon btn-flat btn-primary action-edit" title="Editar"><i class="icon wb-pencil"></i></button>
            <button class="btn btn-sm btn-icon btn-flat btn-danger action-delete" title="Excluir"><i class="icon wb-trash"></i></button>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
