@extends('layouts.app.nosidebar')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/asscrollable/asScrollable.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/examples/css/advanced/scrollable.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">

  <style media="screen">
    .board {
      display: block;
      white-space: nowrap;
      overflow-x: auto;
    }

    .tasks.tasks:not(:last-child) {
      margin-right: 1.25rem;
    }

    .tasks {
      display: inline-block;
      width: 22rem;
      padding: 0 1rem 1rem 1rem;
      vertical-align: top;
      margin-bottom: 24px;
      background-color: #fff;
      border-radius: .2rem;
    }

    .border {
      border: 1px solid #f6f6f7 !important;
    }

    .tasks .task-header {
      padding: 1rem;
      margin: 0 -1rem;
      border-radius: .2rem;
    }

    .task-list-items {
      min-height: 100px;
      position: relative;
    }

    .task-list-items:before {
      content: "(Sem itens)";
      position: absolute;
      line-height: 110px;
      width: 100%;
      text-align: center;
      font-weight: 500;
      font-size: 12px;
    }

    .task-list-items .card {
      cursor: pointer;
    }

    .tasks .card {
      white-space: normal;
      margin-top: 1rem;
    }

    .arrow-none::after {
      display: none !important;
    }

    /*select2 on modal*/
    .modal-open .select2-container {
      z-index: 1701 !important;
    }

  </style>
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/plugins/Sortable/Sortable.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/modules/js/painel_acompanhamento/retrabalho.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/asscrollable.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>

  <script src="{{ asset('assets/js/Plugin/kanban.js') }}"></script>
  <script type="text/javascript">
    coresUrl = "{{ route('materiais.cores_disponiveis', ['id' => '/']) }}/";
    storeRetrabalhoUrl = "{{ route('retrabalhos.store') }}";
    apitoken = "{{ csrf_token() }}";

    $('#idcliente').select2({
      dropdownParent: $('#modalForm')
    });

    function doChangeEvent(evt) {
      var from = $(evt.from);
      var to = $(evt.to);
      var item = $(evt.item);
      var items = $(evt.items);
      var ids = [];

      if (items.length > 0) {
        items.each(function(index, element) {
          ids.push($(element).data('id'));
        });
      } else {
        ids.push(item.data('id'));
      }

      if (to.data('status') === 'T') {
        if (!confirm('Deseja realmente lançar um retrabalho?')) {
          refreshColumn(to.data('status'));
          refreshColumn(from.data('status'));
          return;
        }

        var item_id = item.data('id');
        $.ajax({
          url: "{{ route('painel.infoitem', ['id' => '/']) }}/" + item_id,
          type: 'GET',
          success: function(data) {
            $('#idseparacao').val(data.id);
            $('#idcliente').val(data.cliente_id);
            $('#idcliente').trigger('change');
            $('#retrabalho-modal').modal('show');
          },
          error: function(jqXHR, textStatus, err) {
            console.log(jqXHR);
            window.toastr.error(jqXHR.responseText)
            refreshColumn(to.data('status'));
            refreshColumn(from.data('status'));
          }
        });
      }

      if (from !== to) {
        $.ajax({
          url: "{{ route('painel.move') }}",
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          type: 'POST',
          data: {
            'ids': ids,
            'from': from.data('status'),
            'to': to.data('status')
          },
          success: function(data) {
            refreshColumn(to.data('status'));
            refreshColumn(from.data('status'));
          },
          error: function(jqXHR, textStatus, err) {
            console.log(jqXHR);
            window.toastr.error(jqXHR.responseText)
            refreshColumn(to.data('status'));
            refreshColumn(from.data('status'));
          }
        });
      }
    }

    function arquivarRecebimento(id) {
      $.ajax({
        url: "{{ route('painel.arquivar') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': id
        },
        success: function(data) {
          refreshColumn('R');
        },
        error: function(jqXHR, textStatus, error) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }

    function encerrarSeparacao(id) {
      $.ajax({
        url: "{{ route('painel.encerrar_separacao') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': id
        },
        success: function(data) {
          refreshColumn('S');
        },
        error: function(jqXHR, textStatus, error) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }

    function iniciarPreparacao(id) {
      $.ajax({
        url: "{{ route('painel.iniciar_preparacao') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': id
        },
        success: function(data) {
          refreshColumn('F');
        },
        error: function(jqXHR, textStatus, error) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }

    function iniciarRetrabalho(id) {
      $.ajax({
        url: "{{ route('painel.iniciar_retrabalho') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': id
        },
        success: function(data) {
          refreshColumn('T');
        },
        error: function(jqXHR, textStatus, error) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }

    function iniciarExpedicao(id) {
      $.ajax({
        url: "{{ route('painel.iniciar_expedicao') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        data: {
          'id': id
        },
        success: function(data) {
          refreshColumn('C');
        },
        error: function(jqXHR, textStatus, error) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }

    function refreshColumn(col) {
      var container;
      var route = "{{ route('painel.column') }}";

      switch (col) {
        case 'R':
          container = $('#recebimentos');
          break;
        case 'S':
          container = $('#separacoes');
          break;
        case 'A':
          container = $('#catalogacoes');
          break;
        case 'F':
          container = $('#preparacoes');
          break;
        case 'B':
          container = $('#banhos');
          break;
        case 'T':
          container = $('#retrabalhos');
          break;
        case 'G':
          container = $('#revisoes');
          break;
        case 'C':
          container = $('#expedicoes');
          break;
        case 'L':
          container = $('#concluidos');
          break;
        default:
          break;
      }

      $.ajax({
        url: route,
        type: 'GET',
        data: {
          'col': col
        },
        success: function(data) {
          container.html(data.view);
          //Sortable
          kban = container.find('*[data-plugin="kanban"]');
          opkb = Plugin.getDefaults("kanban");
          opkb.multiDrag = kban.data('multi-drag');
          Sortable.create(kban[0], opkb);
          //Scrollable
          scrlb = container.find('*[data-plugin="scrollable"]');
          opscr = Plugin.getDefaults("scrollable");
          scrlb.asScrollable(opscr);
          filtrar();
        },
        error: function(jqXHR, textStatus, error) {
          window.toastr.error(jqXHR.responseJSON.message)
        }
      });
    }

    //Declaração da nova função (icontains) para usar ao invés do contains
    //Permite busca com case insensitive
    jQuery.expr[':'].icontains = function(a, i, m) {
      return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    function filtrar() {
      var term = $('#search').val();

      if (term != '') {
        $('.card').addClass('d-none');
      } else {
        $('.card').removeClass('d-none');
      }

      $(".text-body:icontains('" + term + "')").parent().parent().parent().removeClass('d-none');
      calcTotais();
    }

    function calcTotais() {
      $('.tasks').each(function(i, coluna) {
        var peso_coluna = 0;
        var qtde_cards = 0;
        $(coluna).find('.card').not('.d-none').each(function(j, bloco) {
          //Peso
          peso_bloco = parseInt($(bloco).find('.badge-peso').html().replace(' g', '').replace(/\./g, ''));
          peso_coluna = peso_coluna + peso_bloco;
          //Quantidade
          qtde_cards = qtde_cards + 1;
        });
        //Caso queira exibir formatado com o separador de milhar:
        //peso_coluna = peso_coluna.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        if (peso_coluna < 1000) {
          peso_coluna = peso_coluna + ' g';
        } else {
          peso_coluna = peso_coluna / 1000;
          peso_coluna = peso_coluna.toFixed(2) + ' Kg';
        }
        //Peso da coluna
        $(coluna).find('.totalizador-peso').html(peso_coluna);
        //Itens da coluna
        $(coluna).find('.totalizador').html(qtde_cards);
      });
    }

    $(document).on('keyup', '#search', function(key) {
      filtrar();
    });

    $(document).on('click', '.action-arquivar', function(event) {
      var id = $(this).parent().parent().parent().parent().data('id');
      arquivarRecebimento(id);
    });

    $(document).on('click', '.action-encerrar-sep', function(event) {
      var id = $(this).parent().parent().parent().parent().data('id');
      encerrarSeparacao(id);
    });

    $(document).on('click', '.action-iniciar-prep', function(event) {
      var id = $(this).parent().parent().parent().parent().data('id');
      iniciarPreparacao(id);
    });

    $(document).on('click', '.action-iniciar-retrab', function(event) {
      var id = $(this).parent().parent().parent().parent().data('id');
      iniciarRetrabalho(id);
    });

    $(document).on('click', '.action-iniciar-exped', function(event) {
      var id = $(this).parent().parent().parent().parent().data('id');
      iniciarExpedicao(id);
    });

  </script>
@endpush

@section('body-class', 'site-menubar-fold site-menubar-fold-alt site-menubar-keep')

@section('content')
  <div class="page-full-h ml-0">

    <div class="page-header">

      <div class="row">
        <div class="col-3">
          <div class="btn-group">
            <a href="{{ route('home') }}" class="btn btn-outline btn-default"><i class="fa fa-home" class="mr-10"></i>
              Início</a>
          </div>
        </div>
        <div class="col-6 text-center">
          <h1 class="page-title font-size-20 font-weight-400 text-uppercase inline-block">Painel de Acompanhamento do
            Processo</h1>
        </div>
        <div class="col-3">
          <form>
            <div class="input-search input-search-dark">
              <i class="input-search-icon wb-search" aria-hidden="true"></i>
              <input type="text" class="form-control" id="search" placeholder="Pesquisar...">
            </div>
          </form>
        </div>
      </div>

    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="board">

            @can('painel_acompanhamento.recebimentos')
              <div id="recebimentos" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('recebimentos'),
                'label' => 'Recebimento',
                'bg_color' => 'bg-teal-400',
                'text_color' => 'blue-grey-700',
                'multi_drag' => true,
                'status' => 'R',
                'table' => 'recebimentos'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.separacoes')
              <div id="separacoes" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('separacoes'),
                'label' => 'Separação',
                'bg_color' => 'bg-orange-500',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'S',
                'table' => 'separacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.catalogacoes')
              <div id="catalogacoes" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('catalogacoes'),
                'label' => 'Catalogando',
                'bg_color' => 'bg-yellow-600',
                'text_color' => 'blue-grey-700',
                'multi_drag' => false,
                'status' => 'A',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.preparacoes')
              <div id="preparacoes" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('preparacoes'),
                'label' => 'Preparação',
                'bg_color' => 'bg-blue-600',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'F',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.banhos')
              <div id="banhos" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('banhos'),
                'label' => 'Banho',
                'bg_color' => 'bg-purple-500',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'B',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.retrabalhos')
              <div id="retrabalhos" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('retrabalhos'),
                'label' => 'Retrabalho',
                'bg_color' => 'bg-brown-500',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'T',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.revisoes')
              <div id="revisoes" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('revisoes'),
                'label' => 'Revisão',
                'bg_color' => 'bg-red-600',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'G',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.expedicoes')
              <div id="expedicoes" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('expedicoes'),
                'label' => 'Peças Prontas - Expedição',
                'bg_color' => 'bg-green-600',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'C',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

            @can('painel_acompanhamento.enviados')
              <div id="concluidos" class="d-inline-block">
                @include('painel_acompanhamento._column', [
                'data' => $painel->get('concluidos'),
                'label' => 'Enviado',
                'bg_color' => 'bg-grey-600',
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'L',
                'table' => 'catalogacoes'
                ])
              </div>
            @endcan

          </div>
        </div>
      </div>
    </div>

  </div>

  @include('painel_acompanhamento._retrabalho')

@endsection
