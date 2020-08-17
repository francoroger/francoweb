@extends('layouts.app.nosidebar')

@push('stylesheets_plugins')
<link rel="stylesheet" href="{{ asset('assets/vendor/asscrollable/asScrollable.css') }}">
<link rel="stylesheet" href="{{ asset('assets/examples/css/advanced/scrollable.css') }}">

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
</style>
@endpush

@push('scripts_plugins')
<script src="{{ asset('assets/plugins/Sortable/Sortable.min.js') }}"></script>
@endpush

@push('scripts_page')
<script src="{{ asset('assets/js/Plugin/asscrollable.js') }}"></script>

<script src="{{ asset('assets/js/Plugin/kanban.js') }}"></script>
<script type="text/javascript">
  //
  //Nested
  //
  /*(function() {
    var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

    // Loop through each nested sortable element
    for (var i = 0; i < nestedSortables.length; i++) {
      new Sortable(nestedSortables[i], {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65
      });
    }
  })();*/

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

    $.ajax({
      url: "{{ route('painel.move') }}",
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
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
      error: function(jqXHR, textStatus, error) {
        console.log(jqXHR);
        alert('Erro: ' + jqXHR.responseText);
        refreshColumn(to.data('status'));
        refreshColumn(from.data('status'));
      }
    });
  }

  function arquivarRecebimento(id)
  {
    $.ajax({
      url: "{{ route('painel.arquivar') }}",
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      type: 'POST',
      data: {
        'id': id
      },
      success: function(data) {
        refreshColumn('R');
      },
      error: function(jqXHR, textStatus, error) {
        console.log(jqXHR);
        alert('Erro: ' + jqXHR.responseText);
      }
    });
  }

  function refreshColumn(col)
  {
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
        container = $('#ordens');
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
        alert('Erro: ' + jqXHR.responseText);
      }
    });
  }

  //Declaração da nova função (icontains) para usar ao invés do contains
  //Permite busca com case insensitive
  jQuery.expr[':'].icontains = function(a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
  };

  function filtrar()
  {
    var term = $('#search').val();

    if (term != '') {
      $('.card').addClass('d-none');
    } else {
      $('.card').removeClass('d-none');
    }

    $(".text-body:icontains('"+term+"')").parent().parent().parent().removeClass('d-none');
  }

  $(document).on('keyup', '#search', function(key) {
    filtrar();
  });

  $(document).on('click', '.action-arquivar', function(event) {
    var id = $(this).parent().parent().parent().parent().data('id');
    arquivarRecebimento(id);
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
            <a href="{{ route('home') }}" class="btn btn-outline btn-default"><i class="fa fa-home" class="mr-10"></i> Início</a>
          </div>
        </div>
        <div class="col-6 text-center">
          <h1 class="page-title font-size-20 font-weight-400 text-uppercase inline-block">Painel de Acompanhamento do Processo</h1>
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
            
            <div id="ordens" class="d-inline-block">
              @include('painel_acompanhamento._column', [
                'data' => $painel->get('ordens'), 
                'label' => 'Preparação / Banho', 
                'bg_color' => 'bg-blue-600', 
                'text_color' => 'text-white',
                'multi_drag' => false,
                'status' => 'F',
                'table' => 'catalogacoes'
              ])
            </div>
            
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
            
          </div>
        </div>
      </div>
    </div>
    
  </div>

@endsection
