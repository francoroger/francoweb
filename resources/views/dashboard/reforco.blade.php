@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/gauge-js/gauge.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/gauge-js/gauge.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/gauge.js') }}"></script>

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
      $.ajax({
        url: "{{ route('api_tanques.registrar') }}",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'POST',
        data: {
          'idtiposervico': $('#idtiposervico').val(),
          'idmaterial': $('#idmaterial').val(),
          'idcor': $('#idcor').val(),
          'milesimos': $('#milesimos').val(),
          'peso': $('#peso').val(),
        },
        success: function (data)
        {
          for(var k in data) {
            var gauge = $(data[k].id).data('gauge');
            gauge.set(data[k].val);
          }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          alert('erro');
          console.log(jqXHR);
        }
      });
    });

    $(document).on('click', '.btn-reforco', function() {
      var id = $(this).data('id');

      $.ajax({
        url: "{{ route('api_tanques.reset') }}",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'POST',
        data: {
          'id': id
        },
        success: function (data)
        {
          var gauge = $('#tanque-'+id).data('gauge');
          gauge.set(0);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          alert('erro');
          console.log(jqXHR);
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
          'idtiposervico': $('#idtiposervico').val(),
          'idmaterial': $('#idmaterial').val(),
          'idcor': $('#idcor').val(),
          'milesimos': $('#milesimos').val()
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
          <button type="button" class="btn btn-success" id="btn-registrar" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

@endsection
