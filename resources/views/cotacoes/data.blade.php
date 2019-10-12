<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th style="width:50%;">Data</th>
      <th style="width:35%;">Valor</th>
      <th class="text-center" style="width:15%;">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($cotacoes as $cotacao)
      <tr>
        <td>{{ date('d/m/Y H:i', strtotime($cotacao->data)) }}</td>
        <td>R$ {{ $cotacao->valorg }}</td>
        <td class="text-center" data-id="{{ $cotacao->id }}">
          <div class="text-nowrap">
            <button class="btn btn-sm btn-icon btn-flat btn-danger action-delete" title="Excluir"><i class="icon wb-trash"></i></button>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<nav>
  {{ $cotacoes->links() }}
</nav>
