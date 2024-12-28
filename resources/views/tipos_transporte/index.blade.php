@extends('layouts.app.main')

@push('scripts_page')
  <script type="text/javascript">
  //Cadastro
  $('#add-form').on('submit', function(event) {
    event.preventDefault();

    $.ajax({
      url: "{{ route('tipos_transporte.store') }}",
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'POST',
      data: {
        'descricao': $('input[name=add-descricao]').val()
      },
      success: function (data)
      {
        $('#add-form')[0].reset();
        $('#transporte').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        window.toastr.error(jqXHR.responseJSON.message)
        console.log(jqXHR);
      }
    });
  });

  //Edição
  $(document).on('click', '.action-edit', function(event) {
    event.preventDefault();
    $('input[name=edit-id]').val($(this).parent().parent().data('id'));
    $('input[name=edit-descricao]').val($(this).parent().parent().parent().find('.row-descricao').html());
    $('#modal-transporte').modal();
  });

  //Foco no campo descrição ao iniciar o modal
  $('#modal-transporte').on('shown.bs.modal', function (e) {
    $('input[name=edit-descricao]').focus();
  });

  //Alteração
  $('#edit-form').on('submit', function(event) {
    event.preventDefault();
    var id = $('input[name=edit-id]').val();

    $.ajax({
      url: "{{ route('tipos_transporte.update', ['id' => '']) }}/" + id,
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'PUT',
      data: {
        'id': id,
        'descricao': $('input[name=edit-descricao]').val()
      },
      success: function (data)
      {
        $('#modal-transporte').modal('hide');
        $('#edit-form')[0].reset();
        $('#transporte').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        window.toastr.error(jqXHR.responseJSON.message)
        console.log(jqXHR);
      }
    });
  });

  //Exclusão
  $(document).on('click', '.action-delete', function(event) {
    event.preventDefault();
    var id = $(this).parent().parent().data('id');

    if (confirm('Deseja excluir o tipo de transporte?')) {
      $.ajax({
        url: "{{ route('tipos_transporte.destroy', ['id' => '']) }}/" + id,
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'DELETE',
        success: function (data)
        {
          $('#transporte').html(data.view);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }
  });
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Tipos de Transporte</h1>
    </div>

    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-7">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Tipos de Transporte</h3>
            </div>
            <div class="panel-body" id="transporte">
              @include('tipos_transporte.data', ['tiposTransporte' => $tiposTransporte])
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Cadastrar Tipo de Transporte</h3>
            </div>
            <div class="panel-body">
              <form id="add-form" autocomplete="off">
                <div class="form-group">
                  <input type="text" class="form-control" id="add-descricao" name="add-descricao" placeholder="Tipo de Transporte">
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

    <div class="modal fade" id="modal-transporte" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-simple">
        <div class="modal-content">
          <form id="edit-form" autocomplete="off">
            <div class="modal-header">
              <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
              <h4 class="modal-title">Editar Tipo de Transporte</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="edit-id">
              <div class="form-group">
                <label for="edit-descricao">Descrição</label>
                <input type="text" class="form-control" id="edit-descricao" name="edit-descricao" placeholder="Tipo de Transporte">
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
