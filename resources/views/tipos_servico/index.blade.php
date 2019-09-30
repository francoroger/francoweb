@extends('layouts.app.main')

@push('scripts_page')
  <script type="text/javascript">
  //Cadastro
  $('#add-form').on('submit', function(event) {
    event.preventDefault();

    $.ajax({
      url: "{{ route('tipos_servico.store') }}",
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'POST',
      data: {
        'descricao': $('input[name=add-descricao]').val(),
        'parametro': $('select[name=add-parametro]').val()
      },
      success: function (data)
      {
        $('#add-form')[0].reset();
        $('#servico').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        alert('erro');
        console.log(jqXHR);
      }
    });
  });

  //Edição
  $(document).on('click', '.action-edit', function(event) {
    event.preventDefault();
    $('input[name=edit-id]').val($(this).parent().parent().data('id'));
    $('input[name=edit-descricao]').val($(this).parent().parent().parent().find('.row-descricao').html());
    $('select[name=edit-parametro]').val($(this).parent().parent().data('parametro'));
    $('#modal-servico').modal();
  });

  //Foco no campo descrição ao iniciar o modal
  $('#modal-servico').on('shown.bs.modal', function (e) {
    $('input[name=edit-descricao]').focus();
  });

  //Alteração
  $('#edit-form').on('submit', function(event) {
    event.preventDefault();
    var id = $('input[name=edit-id]').val();

    $.ajax({
      url: "{{ route('tipos_servico.update', ['id' => '']) }}/" + id,
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'PUT',
      data: {
        'id': id,
        'descricao': $('input[name=edit-descricao]').val(),
        'parametro': $('select[name=edit-parametro]').val()
      },
      success: function (data)
      {
        $('#modal-servico').modal('hide');
        $('#edit-form')[0].reset();
        $('#servico').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        alert('erro');
        console.log(jqXHR);
      }
    });
  });

  //Exclusão
  $(document).on('click', '.action-delete', function(event) {
    event.preventDefault();
    var id = $(this).parent().parent().data('id');

    if (confirm('Deseja excluir o tipo de serviço?')) {
      $.ajax({
        url: "{{ route('tipos_servico.destroy', ['id' => '']) }}/" + id,
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'DELETE',
        success: function (data)
        {
          $('#servico').html(data.view);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          alert('erro');
        }
      });
    }
  });
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Tipos de Serviço</h1>
    </div>

    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-7">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Tipos de Serviço</h3>
            </div>
            <div class="panel-body" id="servico">
              @include('tipos_servico.data', ['tiposServico' => $tiposServico])
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Cadastrar Tipo de Serviço</h3>
            </div>
            <div class="panel-body">
              <form id="add-form" autocomplete="off">
                <div class="form-group">
                  <input type="text" class="form-control" id="add-descricao" name="add-descricao" placeholder="Tipo de Serviço">
                </div>
                <div class="form-group">
                  <select class="form-control" id="add-parametro" name="add-parametro">
                    <option value=""></option>
                    <option value="+">Somar</option>
                    <option value="=">Neutro</option>
                    <option value="-">Subtrair</option>
                  </select>
                </div>
                <div class="form-group text-right">
                  <button type="submit" class="btn btn-info">
                    <i class="icon wb-plus"></i> Adicionar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-servico" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-simple">
        <div class="modal-content">
          <form id="edit-form" autocomplete="off">
            <div class="modal-header">
              <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
              <h4 class="modal-title">Editar Tipo de Serviço</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="edit-id">
              <div class="form-group">
                <label for="edit-descricao">Descrição</label>
                <input type="text" class="form-control" id="edit-descricao" name="edit-descricao" placeholder="Tipo de Serviço">
              </div>
              <div class="form-group">
                <select class="form-control" id="edit-parametro" name="edit-parametro">
                  <option value=""></option>
                  <option value="+">Somar</option>
                  <option value="=">Neutro</option>
                  <option value="-">Subtrair</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Salvar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
