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
  (function() {
      //$('.tasks').asScrollable();
    })();

    function doChangeEvent(evt) {
      var from = $(evt.from);
      var to = $(evt.to);
      var item = $(evt.item)

      $.ajax({
        url: "{{ route('api_catalogacao.update') }}",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        type: 'POST',
        data: {
          'id': item.data('id'),
          'status': to.data('status')
        },
        success: function ()
        {
          from.parent().find('.totalizador').html('(' + from.children('.card').length + ')')
          to.parent().find('.totalizador').html('(' + to.children('.card').length + ')')
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          alert('erro');
          console.log(jqXHR);
        }
      });

    }

    //Declaração da nova função (icontains) para usar ao invés do contains
    //Permite busca com case insensitive
    jQuery.expr[':'].icontains = function(a, i, m) {
      return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    $(document).on('keyup', '#search', function(key) {
      var term = $(this).val();

      if (term != '') {
        $('.card').addClass('d-none');
      } else {
        $('.card').removeClass('d-none');
      }

      $(".text-body:icontains('"+term+"')").parent().parent().parent().removeClass('d-none');
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

            @include('painel_acompanhamento._column', ['data' => $catalogacoes, 'label' => 'Catalogando', 'bg_color' => 'bg-yellow-600', 'text_color' => 'blue-grey-700'])

            @include('painel_acompanhamento._column', ['data' => $ordens, 'label' => 'Preparação / Banho', 'bg_color' => 'bg-blue-600', 'text_color' => 'text-white'])

            @include('painel_acompanhamento._column', ['data' => $revisoes, 'label' => 'Revisão', 'bg_color' => 'bg-red-600', 'text_color' => 'text-white'])

            @include('painel_acompanhamento._column', ['data' => $expedicoes, 'label' => 'Peças Prontas - Expedição', 'bg_color' => 'bg-green-600', 'text_color' => 'text-white'])

            @include('painel_acompanhamento._column', ['data' => $concluidos, 'label' => 'Enviado', 'bg_color' => 'bg-grey-600', 'text_color' => 'text-white'])

          </div>
        </div>
      </div>
    </div>
    

  </div>
@endsection
