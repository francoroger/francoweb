@extends('layouts.app.main')

@push('scripts_page')
  <script type="text/javascript">
  //Paginação
  $(document).on('click', '.pagination a', function(event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $.ajax({
      url: "{{ route('controle_reforco.consulta') }}/?page="+page,
      success: function (data)
      {
        $('#passagens').html(data.view);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        alert('erro');
      }
    });
  });

  //Exclusão
  $(document).on('click', '.action-delete', function(event) {
    event.preventDefault();
    var id = $(this).parent().parent().data('id');

    if (confirm('Deseja excluir essa peça?')) {
      $.ajax({
        url: "{{ route('controle_reforco.destroy', ['id' => '']) }}/" + id,
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'DELETE',
        success: function (data)
        {
          location.href = "{{ route('controle_reforco.consulta') }}"
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          console.log(jqXHR);
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
      <h1 class="page-title font-size-26 font-weight-100">Passagem de Peças</h1>
    </div>

    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Passagem de Peças</h3>
            </div>
            <div class="panel-body" id="passagens">
              @include('consulta_reforco.data', ['passagens' => $passagens])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
