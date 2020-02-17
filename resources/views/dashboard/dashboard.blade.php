@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/nestable/nestable.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/jkanban/dist/jkanban.min.css') }}">
  <style media="screen">
    .kanban-title-board {
      color: #fff;
    }
  </style>
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/nestable/jquery.nestable.js') }}"></script>
  <script src="{{ asset('assets/plugins/jkanban/dist/jkanban.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/nestable.js') }}"></script>

  <script type="text/javascript">
  var kanban2 = new jKanban({
    element: '#demo2',
    gutter: '10px',
    click: function(el){
      $('#myModal').modal('show');
      $('#field-codigo').html(el.dataset.codigo);
      $('#field-cliente').html(el.dataset.cliente);
      $('#field-data').html(el.dataset.data_evento);

      var eid = el.dataset.eid.substring(0,3);
      switch (eid) {
        case 'REC': $('#myModalLabel').html('Recebimento'); break;
        case 'SEP': $('#myModalLabel').html('Separação'); break;
        case 'CAT': $('#myModalLabel').html('Catalogação'); break;
        case 'OS-': $('#myModalLabel').html('Ordem de Serviço'); break;
        case 'REV': $('#myModalLabel').html('Revisão'); break;
        case 'EXP': $('#myModalLabel').html('Expedição'); break;
        default: $('#myModalLabel').html('Detalhes');
      }

    },
    boards: [
      {
        'id': '_receb',
        'title': 'Recebimento',
        'class': 'bg-orange-600',
        'item': []
      },
      {
        'id': '_separ',
        'title': 'Separação',
        'class': 'bg-blue-grey-600',
        'item': []
      },
      {
        'id': '_catalog',
        'title': 'Catalogação',
        'class': 'bg-blue-600',
        'item': []
      },
      {
        'id': '_os',
        'title': 'O.S.',
        'class': 'bg-red-600',
        'item': []
      },
      {
        'id': '_rev',
        'title': 'Revisão',
        'class': 'bg-teal-600',
        'item': []
      },
      {
        'id': '_exped',
        'title': 'Expedição',
        'class': 'bg-purple-600',
        'item': []
      },
      ]
  });

  var xmlhttpRec = new XMLHttpRequest();
  xmlhttpRec.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var receb = JSON.parse(this.responseText);
      for(var k in receb) {
        kanban2.addElement('_receb', receb[k]);
      }
    }
  };
  xmlhttpRec.open("GET", "{{ route('api_recebimento') }}", true);
  xmlhttpRec.send();

  var xmlhttpCat = new XMLHttpRequest();
  xmlhttpCat.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var catalog = JSON.parse(this.responseText);
      for(var k in catalog) {
        kanban2.addElement('_catalog', catalog[k]);
      }
    }
  };
  xmlhttpCat.open("GET", "{{ route('api_catalogacao') }}", true);
  xmlhttpCat.send();

  var xmlhttpOs = new XMLHttpRequest();
  xmlhttpOs.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var os = JSON.parse(this.responseText);
      for(var k in os) {
        kanban2.addElement('_os', os[k]);
      }
    }
  };
  xmlhttpOs.open("GET", "{{ route('api_os') }}", true);
  xmlhttpOs.send();

  var xmlhttpRev = new XMLHttpRequest();
  xmlhttpRev.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var rev = JSON.parse(this.responseText);
      for(var k in rev) {
        kanban2.addElement('_rev', rev[k]);
      }
    }
  };
  xmlhttpRev.open("GET", "{{ route('api_revisao') }}", true);
  xmlhttpRev.send();

  var xmlhttpExp = new XMLHttpRequest();
  xmlhttpExp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var exped = JSON.parse(this.responseText);
      for(var k in exped) {
        kanban2.addElement('_exped', exped[k]);
      }
    }
  };
  xmlhttpExp.open("GET", "{{ route('api_expedicao') }}", true);
  xmlhttpExp.send();

  </script>
@endpush

@section('body-class', 'site-menubar-fold site-menubar-fold-alt site-menubar-keep')

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Painel de Acompanhamento do Processo</h1>
    </div>
    <div class="page-content container-fluid">

      <div class="panel">
        <div class="panel-body">
          <div class="row m-b-30">
            <div class="col-md-12">
              <div id="demo2"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Detalhes</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <table class="table table-bordered">
            <tbody>
              <tr>
                <th class="bg-blue-grey-100" style="width:20%">Código</th>
                <td id="field-codigo"></td>
              </tr>
              <tr>
                <th class="bg-blue-grey-100" style="width:20%">Cliente</th>
                <td id="field-cliente"></td>
              </tr>
              <tr>
                <th class="bg-blue-grey-100" style="width:20%">Data</th>
                <td id="field-data"></td>
              </tr>
            </tbody>
          </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

@endsection
