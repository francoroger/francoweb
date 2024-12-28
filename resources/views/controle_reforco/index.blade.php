@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/gauge-js/gauge.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">

  <style media="screen">
    .modal-open .select2-container {
      z-index: 1701 !important;
    }

    @media only screen and (max-width: 800px) {

      /* Force table to not be like tables anymore */
      #no-more-tables table,
      #no-more-tables thead,
      #no-more-tables tbody,
      #no-more-tables th,
      #no-more-tables td,
      #no-more-tables tr {
        display: block;
      }

      /* Hide table headers (but not display: none;, for accessibility) */
      #no-more-tables thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      #no-more-tables tr {
        border: 1px solid #ccc;
      }

      #no-more-tables td {
        /* Behave like a "row" */
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 30%;
        white-space: normal;
        text-align: left;
      }

      #no-more-tables td:before {
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: 400;
      }

      /*
                                        Label the data
                                        */
      #no-more-tables td:before {
        content: attr(data-title);
      }
    }

  </style>
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/gauge-js/gauge.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/formatter/jquery.formatter.js') }}"></script>
  <script src="{{ asset('assets/vendor/moment/moment.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/gauge.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/toastr.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-sweetalert.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/formatter.js') }}"></script>

  <script type="text/javascript">
    $('#idcliente').select2({
      dropdownParent: $('#modalForm')
    });

    function validaItens() {
      let result = true;
      $('.item-passagem').each(function(i, v) {
        let check = $(v).find('select[name*="idtiposervico"]');
        if (check.val() != '') {
          //valida linha
          if ($(v).find('select[name*="idmaterial"]').val() == '') {
            toastr.error("Informe o material!");
            result = false;
          }
          if ($(v).find('input[name*="peso"]').val() == '') {
            toastr.error("Informe o peso!");
            result = false;
          }
        }
      });
      return result;
    }

    $(document).on('click', '#btn-registrar', function() {
      $(this).prop('disabled', true);
      if ($('#data_servico').val() == '') {
        toastr.error("Informe a data do serviço!");
        $(this).prop('disabled', false);
        return false;
      } else if ($('#hora_servico').val() == '') {
        toastr.error("Informe a hora do serviço!");
        $(this).prop('disabled', false);
        return false;
      } else if (!validaItens()) {
        $(this).prop('disabled', false);
        return false;
      } else {
        //valida a data atual
        var data_servico = $('#data_servico').val();
        var hora_servico = $('#hora_servico').val();
        var moment_serv = moment(data_servico + ' ' + hora_servico, 'DD/MM/YYYY hh:mm:ss');
        var moment_now = moment();
        if (moment_serv.isAfter(moment_now)) {
          toastr.error("A data/hora do serviço não pode ser maior que a atual!");
          $(this).prop('disabled', false);
          return false;
        } else {
          let dados = $('#passagem-form').serializeArray();
          $.ajax({
            url: "{{ route('api_tanques.registrar') }}",
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: 'POST',
            data: dados,
            success: function(data) {
              for (var k in data) {
                var gauge = $('#tanque-' + data[k].id).data('gauge');
                $('#tanque-' + data[k].id).data('value', data[k].val);
                gauge.set(data[k].val);
                if (data[k].exd) {
                  $('#excedente-' + data[k].id).html(data[k].exd);
                  $('#pnl-' + data[k].id).addClass('panel-danger');
                  $('#pnl-' + data[k].id).addClass('border-danger');
                  $('#pnl-' + data[k].id).find('.panel-desc').addClass('text-white');
                } else {
                  $('#excedente-' + data[k].id).html("&nbsp;");
                  $('#pnl-' + data[k].id).removeClass('panel-danger');
                  $('#pnl-' + data[k].id).removeClass('border-danger');
                  $('#pnl-' + data[k].id).find('.panel-desc').removeClass('text-white');
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              window.toastr.error(jqXHR.responseJSON.message)
              console.log(jqXHR);
            }
          });

          //fecha modal
          $('#modalForm').modal('hide');
          $(this).prop('disabled', false);
        }
      }
    });

    $(document).on('click', '.btn-reforco', function() {
      var id = $(this).data('id');
      var descricao = $(this).data('descricao');

      swal({
          title: "Confirmar Reforço",
          text: "Confirma o reforço no tanque " + descricao + "?",
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
              url: "{{ route('api_tanques.reforco') }}",
              headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              type: 'POST',
              data: {
                'id': id
              },
              success: function(data) {
                for (var k in data) {
                  var gauge = $('#tanque-' + data[k].id).data('gauge');
                  $('#tanque-' + data[k].id).data('value', data[k].val);
                  gauge.set(data[k].val);
                  if (data[k].exd) {
                    $('#excedente-' + data[k].id).html(data[k].exd);
                    $('#pnl-' + data[k].id).addClass('panel-danger');
                    $('#pnl-' + data[k].id).addClass('border-danger');
                    $('#pnl-' + data[k].id).find('.panel-desc').addClass('text-white');
                  } else {
                    $('#excedente-' + data[k].id).html("&nbsp;");
                    $('#pnl-' + data[k].id).removeClass('panel-danger');
                    $('#pnl-' + data[k].id).removeClass('border-danger');
                    $('#pnl-' + data[k].id).find('.panel-desc').removeClass('text-white');
                  }
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                window.toastr.error(jqXHR.responseJSON.message)
                console.log(jqXHR);
              }
            });
          }
        });
    });

    $(document).on('click', '.desfazer_reforco', function(event) {
      event.preventDefault();
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
              headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              type: 'POST',
              data: {
                'id': id
              },
              success: function(data) {
                for (var k in data) {
                  var gauge = $('#tanque-' + data[k].id).data('gauge');
                  $('#tanque-' + data[k].id).data('value', data[k].val);
                  gauge.set(data[k].val);
                  if (data[k].exd) {
                    $('#excedente-' + data[k].id).html(data[k].exd);
                    $('#pnl-' + data[k].id).addClass('panel-danger');
                    $('#pnl-' + data[k].id).addClass('border-danger');
                    $('#pnl-' + data[k].id).find('.panel-desc').addClass('text-white');
                  } else {
                    $('#excedente-' + data[k].id).html("&nbsp;");
                    $('#pnl-' + data[k].id).removeClass('panel-danger');
                    $('#pnl-' + data[k].id).removeClass('border-danger');
                    $('#pnl-' + data[k].id).find('.panel-desc').removeClass('text-white');
                  }
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                window.toastr.error(jqXHR.responseJSON.message)
                console.log(jqXHR);
              }
            });
          }
        });
    });

    $(document).on('click', '.reset_tanque', function(event) {
      event.preventDefault();
      var id = $(this).data('id');

      swal({
          title: "Confirmação",
          text: "Deseja realmente REINICIAR a contagem do tanque? ATENÇÃO: essa ação removerá todas as passagens no tanque",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          //cancelButtonClass: "btn-danger",
          confirmButtonText: 'Confirmar RESET!',
          cancelButtonText: 'Não',
          closeOnConfirm: true,
        },
        function(isConfirm) {
          if (isConfirm) {
            $.ajax({
              url: "{{ route('api_tanques.reset') }}",
              headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              type: 'POST',
              data: {
                'id': id
              },
              success: function(data) {
                for (var k in data) {
                  var gauge = $('#tanque-' + data[k].id).data('gauge');
                  $('#tanque-' + data[k].id).data('value', data[k].val);
                  gauge.set(data[k].val);
                  if (data[k].exd) {
                    $('#excedente-' + data[k].id).html(data[k].exd);
                    $('#pnl-' + data[k].id).addClass('panel-danger');
                    $('#pnl-' + data[k].id).addClass('border-danger');
                  } else {
                    $('#excedente-' + data[k].id).html("&nbsp;");
                    $('#pnl-' + data[k].id).removeClass('panel-danger');
                    $('#pnl-' + data[k].id).removeClass('border-danger');
                  }
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                window.toastr.error(jqXHR.responseJSON.message)
                console.log(jqXHR);
              }
            });
          }
        });
    });

    $(document).on('click', '.reforco_analise', function(event) {
      event.preventDefault();
      var id = $(this).data('id');

      var current_val = $(this).parent().parent().parent().parent().parent().parent().find('.panel-body').find(
        '.gauge').data('value');
      $('#reforco_analise_id').val(id);
      $('#reforco_analise_valor').val(Math.round(current_val));
      $('#modalReforco').modal('show');
    });

    $(document).on('click', '#btn-analise', function(event) {
      $.ajax({
        url: "{{ route('api_tanques.reforco_analise') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': $('#reforco_analise_id').val() ? $('#reforco_analise_id').val() : '',
          'reforco_analise_valor': $('#reforco_analise_valor').val() ? $('#reforco_analise_valor').val() : '0',
          'reforco_analise_motivo': $('#reforco_analise_motivo').val(),
        },
        success: function(data) {
          for (var k in data) {
            var gauge = $('#tanque-' + data[k].id).data('gauge');
            $('#tanque-' + data[k].id).data('value', data[k].val);
            gauge.set(data[k].val);
            if (data[k].exd) {
              $('#excedente-' + data[k].id).html(data[k].exd);
              $('#pnl-' + data[k].id).addClass('panel-danger');
              $('#pnl-' + data[k].id).addClass('border-danger');
              $('#pnl-' + data[k].id).find('.panel-desc').addClass('text-white');
            } else {
              $('#excedente-' + data[k].id).html("&nbsp;");
              $('#pnl-' + data[k].id).removeClass('panel-danger');
              $('#pnl-' + data[k].id).removeClass('border-danger');
              $('#pnl-' + data[k].id).find('.panel-desc').removeClass('text-white');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          window.toastr.error(jqXHR.responseJSON.message)
          console.log(jqXHR);
        }
      });

      //fecha modal
      $('#modalReforco').modal('hide');
    });

    $(document).on('click', '.reforco_comentario', function(event) {
      event.preventDefault();
      var id = $(this).data('id');

      var current_val = $(this).parent().parent().parent().parent().parent().parent().find('.panel-body').find(
        '.gauge').data('value');
      $('#reforco_comentario_id').val(id);
      $('#modalComentario').modal('show');
    });

    $(document).on('click', '#btn-comentario', function(event) {
      $.ajax({
        url: "{{ route('api_tanques.comentario') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': $('#reforco_comentario_id').val() ? $('#reforco_comentario_id').val() : '',
          'reforco_comentario': $('#reforco_comentario').val(),
        },
        success: function(data) {

        },
        error: function(jqXHR, textStatus, errorThrown) {
          window.toastr.error(jqXHR.responseJSON.message)
          console.log(jqXHR);
        }
      });

      //fecha modal
      $('#modalComentario').modal('hide');
    });

    //Add Item Passagem
    $(document).on('click', '.btn-add-item-passagem', function() {
      let rowbase = $('.item-passagem').last();
      let container = rowbase.parent();
      let clone = rowbase.clone();
      let index = parseInt(clone.data('index'));
      let cloneindex = index + 1;

      clone.attr('data-index', cloneindex);
      clone.find('input, select').each(function(i, elem) {
        $(elem).attr('name', $(elem).attr('name').replace(index, cloneindex));
        $(elem).val('');
      });

      rowbase.find('.item-passagem-controls').removeClass('d-none').addClass('d-flex');
      rowbase.find('.btn-add-item-passagem').addClass('d-none');

      container.append(clone);
    });

    //Del Item Passagem
    $(document).on('click', '.btn-remove-item-passagem', function(e) {
      e.preventDefault();
      let row = $(this).parent().parent().parent();
      row.remove();

    });

    $(document).on('change', 'select[name*="idmaterial"]', function() {
      let id = $(this).val();
      let cbCores = $(this).parent().parent().find('select[name*="idcor"]');

      if (id == '') {
        cbCores.empty();
        cbCores.append(`<option></option>`);
      } else {
        preencheCores(id, cbCores);
      }
    });

    function limpaCampos() {
      $('#idcliente').val('');
      $('#idcliente').trigger('change');
      let lim = $('.item-passagem').length - 1;
      $('.item-passagem').each(function(i, v) {
        if (i < lim) {
          $(v).remove();
        } else {
          $(v).find('input, select').each(function(j, elem) {
            $(elem).val('');
          });
          $(v).attr('data-index', '0');
        }
      });

      var currentdate = new Date();
      //var data = currentdate.getDate().toString().padStart(2, "0") + "-" + (currentdate.getMonth()+1).toString().padStart(2, "0") + '-' + currentdate.getFullYear();
      var hora = currentdate.getHours().toString().padStart(2, "0") + ":" + currentdate.getMinutes().toString().padStart(
        2, "0") + ":" + currentdate.getSeconds().toString().padStart(2, "0");

      $('#hora_servico').val(hora);
      $('#data_servico').datepicker("update", new Date());
    }

    //Preenche Cores
    function preencheCores(id, cbCores) {
      return $.ajax({
        url: "{{ route('materiais.cores_disponiveis', ['id' => '/']) }}/" + id,
        dataType: "json",
        success: function(data) {
          cbCores.empty();
          cbCores.append(`<option></option>`);

          for (let k in data) {
            cbCores.append(`<option value="${data[k].id}">${data[k].descricao}</option>`);
          }
        }
      });
    }

    $('#modalForm').on('show.bs.modal', function(e) {
      limpaCampos();
    })

  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100"><span class="hidden-sm-down">Controle de </span>Reforço<span
          class="hidden-sm-down"> de Tanques</span></h1>
      <div class="page-header-actions">
        <a class="btn btn-icon btn-info btn-outline" href="{{ route('controle_reforco.consulta') }}"
          data-placement="bottom" data-toggle="tooltip" data-original-title="Consultar">
          <i class="fa fa-search"></i>
        </a>
        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modalForm">
          <i class="icon wb-check-circle" aria-hidden="true"></i>
          <span class="hidden-sm-down">Passagem de Peças</span>
        </a>
      </div>
    </div>
    <div class="page-content container-fluid" id="ciclos-reforco">.

      @include('controle_reforco.data', ['tanques' => $tanques])

    </div>
  </div>

  <!-- Modal passagem -->
  <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFormLabel">Passagem de Peças</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="passagem-form">
            <div class="row">
              <div class="form-group col-md-6">
                <label class="form-control-label font-weight-400" for="data_servico">Data</label>
                <input type="text" class="form-control" id="data_servico" name="data_servico" data-plugin="datepicker"
                  data-language="pt-BR" />
              </div>

              <div class="form-group col-md-6">
                <label class="form-control-label font-weight-400" for="hora_servico">Hora</label>
                <input type="text" class="form-control" id="hora_servico" name="hora_servico" data-plugin="formatter"
                  data-pattern="[[99]]:[[99]]:[[99]]" />
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-12">
                <label class="form-control-label font-weight-400" for="idcliente">Cliente</label>
                <select class="form-control" id="idcliente" name="idcliente" style="width:100%;">
                  <option value=""></option>
                  @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->ativo ? '' : ' disabled' }}>
                      {{ $cliente->identificacao }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div id="no-more-tables">
                  <table class="col-sm-12 table table-condensed cf p-0" id="tb-item-passagem">
                    <thead class="cf">
                      <tr>
                        <th class="w-p25">Serviço</th>
                        <th class="w-p25">Material</th>
                        <th class="w-p20">Cor</th>
                        <th class="w-p10">Ml</th>
                        <th class="w-p15">Peso</th>
                        <th class="w-p5"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="item-passagem" data-index="0">
                        <td data-title="Serviço">
                          <select class="form-control" name="item_passagem[0][idtiposervico]">
                            <option value=""></option>
                            @foreach ($tiposServico as $tipoServico)
                              <option value="{{ $tipoServico->id }}">{{ $tipoServico->descricao }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td data-title="Material">
                          <select class="form-control" name="item_passagem[0][idmaterial]">
                            <option value=""></option>
                            @foreach ($materiais as $material)
                              <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td data-title="Cor">
                          <select class="form-control" name="item_passagem[0][idcor]">
                            <option value=""></option>
                          </select>
                        </td>
                        <td data-title="Ml">
                          <input type="number" class="form-control" name="item_passagem[0][milesimos]" min="0" />
                        </td>
                        <td data-title="Peso">
                          <input type="number" class="form-control" name="item_passagem[0][peso]" min="0" />
                        </td>
                        <td data-title="Ações">
                          <input type="hidden" name="item_passagem[0][item_id]">
                          <div class="item-passagem-controls d-none justify-content-center">
                            <button type="button" class="btn btn-sm btn-block btn-outline-danger btn-remove-item-passagem"
                              title="Excluir"><i class="fa fa-times"></i></button>
                          </div>
                          <button type="button" class="btn btn-sm btn-block btn-info btn-add-item-passagem"
                            title="Adicionar item"><i class="icon wb-plus"></i></button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btn-registrar"><i class="fa fa-tick"></i> OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal reforço -->
  <div class="modal fade" id="modalReforco" tabindex="-1" role="dialog" aria-labelledby="modalReforcoLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalReforcoLabel">Reforço por Análise</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="reforco_analise_id" id="reforco_analise_id">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="reforco_analise_valor">Valor do tanque após análise</label>
              <input type="number" class="form-control" id="reforco_analise_valor" name="reforco_analise_valor" min="0" />
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="reforco_analise_motivo">Motivo</label>
              <textarea class="form-control" id="reforco_analise_motivo" name="reforco_analise_motivo"
                rows="5"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btn-analise"><i class="fa fa-tick"></i> OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal comentário -->
  <div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-labelledby="modalComentarioLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalComentarioLabel">Observação</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="reforco_comentario_id" id="reforco_comentario_id">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="reforco_comentario">Observações</label>
              <textarea class="form-control" id="reforco_comentario" name="reforco_comentario" rows="5"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btn-comentario"><i class="fa fa-tick"></i> OK</button>
        </div>
      </div>
    </div>
  </div>

@endsection
