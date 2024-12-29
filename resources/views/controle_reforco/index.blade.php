@extends('layouts.app.main')

@push('stylesheets_plugins')
  {{ Log::info('Carregando stylesheets plugins') }}
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
  {{ Log::info('Stylesheets plugins carregados') }}
@endpush

@push('scripts_plugins')
  {{ Log::info('Carregando scripts plugins') }}
  <script src="{{ asset('assets/vendor/gauge-js/gauge.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/formatter/jquery.formatter.js') }}"></script>
  <script src="{{ asset('assets/vendor/moment/moment.min.js') }}"></script>
  {{ Log::info('Scripts plugins carregados') }}
@endpush

@push('scripts_page')
  {{ Log::info('Carregando scripts page') }}
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
  {{ Log::info('Scripts page carregados') }}
@endpush

@section('content')
  {{ Log::info('Iniciando seção content') }}
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
  {{ Log::info('Seção content finalizada') }}
@endsection
