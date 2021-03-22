@extends('layouts.app.main')

@push('scripts_plugins')
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/modules/js/cotacoes/index.js') }}"></script>
  <script type="text/javascript">
  //Cadastro
  $('#add-form').on('submit', function(event) {
    event.preventDefault();

    $.ajax({
      url: "{{ route('cotacoes.store') }}",
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'POST',
      data: {
        'idmaterial': '{{ $material->id }}',
        'data': $('input[name=add-data]').val(),
        'valorg': $('input[name=add-valorg]').val()
      },
      success: function (data)
      {
        $('#add-form')[0].reset();
        $('#cotacoes').html(data.view);
        getCotacaoAtual();
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

    if (confirm('Deseja excluir a cotação?')) {
      $.ajax({
        url: "{{ route('cotacoes.destroy', ['id' => '']) }}/" + id,
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'DELETE',
        success: function (data)
        {
          var el = $(".table").find("[data-id='" + id + "']");
          el.parent().remove();

          getCotacaoAtual();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }
  });

  //Paginação do Preview
  $(document).on('click', '.pagination li a', function(event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $.ajax({
      url: "{{ route('materiais.cotacoes', $material->id) }}?page="+page,
      type: 'GET',
      success: function (data)
      {
        $('#cotacoes').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        window.toastr.error(jqXHR.responseJSON.message)
      }
    });
  });

  //Cotação atual
  function getCotacaoAtual() {
    return $.ajax({
      url: "{{ route('materiais.cotacao', $material->id) }}",
      type: 'GET',
      success: function (data)
      {
        $('#cotacao-atual').html('R$ '+data.valorg);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        window.toastr.error(jqXHR.responseJSON.message)
      }
    });
  }
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Cotações de {{ $material->descricao }}</h1>
      <div class="page-header-actions">
        <div class="counter inline-block text-right mr-20 hidden-sm-down">
          <div class="counter-label">Cotação Atual</div>
          <span class="counter-number font-weight-medium" id="cotacao-atual">R$ {{ $material->cotacoes->count() > 0 ? $material->cotacoes->sortByDesc('data')->first()->valorg : '0,00' }}</span>
        </div>
      </div>
    </div>

    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-7">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Histórico de Cotações</h3>
            </div>
            <div class="panel-body" id="cotacoes">
              @include('cotacoes.data', ['cotacoes' => $cotacoes])
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Cadastrar Cotação</h3>
            </div>
            <div class="panel-body">
              <form id="add-form" class="form-horizontal" autocomplete="off">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Data/Hora</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="add-data" name="add-data" value="{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label">Valor (g)</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="add-valorg" name="add-valorg">
                  </div>
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

  </div>
@endsection
