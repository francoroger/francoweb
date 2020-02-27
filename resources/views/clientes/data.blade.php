<table class="table table-vcenter table-striped">
  <thead>
    <tr>
      <th>Descrição</th>
      <th class="d-none d-sm-table-cell" style="width: 15%;">Status</th>
      <th class="text-center" style="width: 100px;">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($categories as $category)
      <tr>
        <td class="row-name">{{ $category->name }}</td>
        <td class="d-none d-sm-table-cell row-status" data-status="{{ $category->is_active }}">
          @if ($category->is_active)
            <span class="badge badge-success">Habilitada</span>
          @else
            <span class="badge badge-secondary">Desabilitada</span>
          @endif
        </td>
        <td class="text-center" data-id="{{ $category->id }}">
          <div class="btn-group">
            <button type="button" class="btn btn-sm btn-alt-primary js-tooltip-enabled action-edit" data-toggle="tooltip" title="" data-original-title="Editar">
              <i class="fa fa-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-alt-danger js-tooltip-enabled action-delete" data-toggle="tooltip" title="" data-original-title="Excluir">
              <i class="fa fa-times"></i>
            </button>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
