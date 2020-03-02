@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/gauge-js/gauge.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/gauge-js/gauge.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/gauge.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/toastr.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>

  <script type="text/javascript">
    $(document).on('change', '#idtiposervico, #idmaterial, #idcor, #milesimos', function() {
      listaTanques();
    });

    $(document).on('change', '#idmaterial', function() {
      var id = $(this).val();

      if (id == '') {
        var cbCores = document.getElementById("idcor");
        cbCores.options.length = 0;
        cbCores.add(document.createElement("option"));
      } else {
        $.ajax({
          url: "{{ route('materiais.cores_disponiveis', ['id' => '/']) }}/" + id,
          dataType: "json",
          success: function (data)
          {
            var cbCores = document.getElementById("idcor");
            cbCores.options.length = 0;
            cbCores.add(document.createElement("option"));

            for(var k in data) {
              var option = document.createElement("option");
              option.value = data[k].id;
              option.text = data[k].descricao;
              cbCores.add(option);
            }
          }
        });
      }
    });

    $(document).on('click', '#btn-registrar', function() {
      if($('#idtiposervico').val() == '') {
        toastr.error("Informe o tipo de serviço!");
        return false;
      } else if($('#idmaterial').val() == '') {
        toastr.error("Informe o material!");
        return false;
      } else if($('#peso').val() == '') {
        toastr.error("Informe o peso!");
        return false;
      } else {
        $.ajax({
          url: "{{ route('api_tanques.registrar') }}",
          headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
          type: 'POST',
          data: {
            'idtiposervico': $('#idtiposervico').val() ? $('#idtiposervico').val() : '',
            'idmaterial': $('#idmaterial').val() ? $('#idmaterial').val() : '',
            'idcor': $('#idcor').val() ? $('#idcor').val() : '',
            'milesimos': $('#milesimos').val() ? $('#milesimos').val() : '',
            'peso': $('#peso').val(),
          },
          success: function (data)
          {
            for(var k in data) {
              var gauge = $('#tanque-'+data[k].id).data('gauge');
              gauge.set(data[k].val);
              if (data[k].exd) {
                $('#excedente-'+data[k].id).html(data[k].exd);
              } else {
                $('#excedente-'+data[k].id).html("&nbsp;");
              }
            }
          },
          error: function(jqXHR, textStatus, errorThrown)
          {
            alert('erro');
            console.log(jqXHR);
          }
        });

        //fecha modal
        $('#modalForm').modal('hide');
      }

    });

    $(document).on('click', '.btn-reforco', function() {
      var id = $(this).data('id');
      var descricao = $(this).data('descricao');

      swal({
        title: "Confirmar Reforço",
        text: "Confirma o reforço no tanque "+descricao+"?",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        //cancelButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não',
        closeOnConfirm: true,
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url: "{{ route('api_tanques.reset') }}",
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            type: 'POST',
            data: {
              'id': id
            },
            success: function (data)
            {
              for(var k in data) {
                var gauge = $('#tanque-'+data[k].id).data('gauge');
                gauge.set(data[k].val);
                if (data[k].exd) {
                  $('#excedente-'+data[k].id).html(data[k].exd);
                } else {
                  $('#excedente-'+data[k].id).html("&nbsp;");
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              alert('erro');
              console.log(jqXHR);
            }
          });
        }
      });
    });

    $(document).on('click', '.desfazer_reforco', function() {
      var id = $(this).data('id');

      swal({
        title: "Confirmação",
        text: "Deseja realmente DESFAZER o último reforço?",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        //cancelButtonClass: "btn-danger",
        confirmButtonText: 'Sim, Desfazer!',
        cancelButtonText: 'Não',
        closeOnConfirm: true,
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url: "{{ route('api_tanques.undo') }}",
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            type: 'POST',
            data: {
              'id': id
            },
            success: function (data)
            {
              for(var k in data) {
                var gauge = $('#tanque-'+data[k].id).data('gauge');
                gauge.set(data[k].val);
                if (data[k].exd) {
                  $('#excedente-'+data[k].id).html(data[k].exd);
                } else {
                  $('#excedente-'+data[k].id).html("&nbsp;");
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              alert('erro');
              console.log(jqXHR);
            }
          });
        }
      });
    });

    function listaTanques()
    {
      $.ajax({
        url: "{{ route('api_tanques') }}",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'POST',
        data: {
          'idtiposervico': $('#idtiposervico').val() ? $('#idtiposervico').val() : '',
          'idmaterial': $('#idmaterial').val() ? $('#idmaterial').val() : '',
          'idcor': $('#idcor').val() ? $('#idcor').val() : '',
          'milesimos': $('#milesimos').val() ? $('#milesimos').val() : ''
        },
        success: function (data)
        {
          $('#tab-tanques tbody').empty();
          for (var k in data) {
            $('#tab-tanques tbody').append('<tr><td>'+data[k].descricao+'</td></tr>')
          }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          console.log(jqXHR);
        }
      });

    }

    function limpaCampos()
    {
      $('#idtiposervico').val('');
      $('#idmaterial').val('');
      $('#idcor').val('');
      $('#milesimos').val('');
      $('#peso').val('');
    }

    $('#modalForm').on('show.bs.modal', function (e) {
      limpaCampos();
    })

  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Controle de Reforço de Tanques</h1>
      <div class="page-header-actions">
        <div class="btn-group btn-group-sm" aria-label="Ações" role="group">
          <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modalForm">
            <i class="icon wb-check-circle" aria-hidden="true"></i>
            <span class="hidden-sm-down">Passagem de Peças</span>
          </a>
        </div>
      </div>
    </div>
    <div class="page-content container-fluid" id="ciclos-reforco">.

      @include('dashboard.data_reforco', ['tanques' => $tanques])

    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFormLabel">Passagem de Peças</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="form-group col-md-6">
              <label class="form-control-label" for="idtiposervico">Serviço</label>
              <select class="form-control" id="idtiposervico" name="idtiposervico">
                <option value=""></option>
                @foreach ($tiposServico as $tipoServico)
                  <option value="{{ $tipoServico->id }}">{{ $tipoServico->descricao }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-6">
              <label class="form-control-label" for="idmaterial">Material</label>
              <select class="form-control" id="idmaterial" name="idmaterial">
                <option value=""></option>
                @foreach ($materiais as $material)
                  <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-6">
              <label class="form-control-label" for="idcor">Cor</label>
              <select class="form-control" id="idcor" name="idcor">
                <option value=""></option>
              </select>
            </div>

            <div class="form-group col-md-3">
              <label class="form-control-label" for="milesimos">Milésimos</label>
              <input type="number" class="form-control" id="milesimos" name="milesimos" min="0" />
            </div>

            <div class="form-group col-md-3">
              <label class="form-control-label" for="peso">Peso</label>
              <input type="number" class="form-control" id="peso" name="peso" min="0" />
            </div>

          </div>

          <div class="row">
            <div class="col-md-12">
              <table class="table table-sm" id="tab-tanques">
                <thead>
                  <tr>
                    <th style="border-bottom:none;">
                      <a class="font-size-12" data-toggle="collapse" href="#siteMegaCollapseOne" data-parent="#siteMegaAccordion"
                      aria-expanded="false" aria-controls="siteMegaCollapseOne">Ver Tanques</a>
                    </th>
                  </tr>
                </thead>
                <tbody class="collapse" id="siteMegaCollapseOne"></tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btn-registrar"><i class="fa fa-tick"></i> OK</button>
        </div>
      </div>
    </div>
  </div>

@endsection
