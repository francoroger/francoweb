@extends('layouts.app.main')

@push('stylesheets_plugins')
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
    border: 1px solid #f6f6f7!important;
  }

  .tasks .task-header {
    background-color: #fff;
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
  <script src="{{ asset('assets/js/Plugin/kanban.js') }}"></script>
  <script type="text/javascript">
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
  </script>
@endpush

@section('body-class', 'site-menubar-fold site-menubar-fold-alt site-menubar-keep')

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Painel de Acompanhamento do Processo</h1>
    </div>
    <div class="page-content container-fluid">

      <div class="row">
        <div class="col-md-12">

          <div class="board">
            <div class="tasks border">
              <h5 class="mt-0 task-header header-title bg-yellow-600">
                Catalogando <span class="font-size-12 totalizador">({{ $catalogacoes->count() }})</span>

                <br>

                <span class="font-size-14 totalizador-peso">{{ number_format($catalogacoes->sum('peso_total_itens'), 2, ',', '.') }} g</span>
              </h5>

              <div id="task-list-catalog" class="task-list-items" data-plugin="kanban" data-status="A">
                @foreach ($catalogacoes as $item)
                  <!-- Task Item -->
                  <div class="card border mb-0" data-id="{{ $item->id }}">
                    <div class="card-body p-5">
                      <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown" aria-expanded="false">
                          <i class="icon wb-more-vertical px-5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                          <div class="dropdown-divider"></div>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                        </div>
                      </div>
                      <h6 class="mt-0 mb-2 font-size-12">
                        <a href="#" data-toggle="modal" data-target="#task-detail-modal" class="text-body">{{ $item->cliente->nome ?? '' }} ({{$item->id }})</a>
                      </h6>

                      <span class="badge badge-outline badge-primary font-size-12 font-weight-500">{{ number_format($item->itens->sum('peso'), 2, ',', '.') }} g</span>

                      <!--
                      <span class="badge badge-outline badge-danger">Alta</span>
                      -->

                      <p class="mb-0 mt-4">
                        <!--
                        <img src="{{ asset('assets/portraits/0.png') }}" alt="user-img" class="avatar-xs rounded-circle mr-2" />
                        -->

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->count() }} itens">
                          <i class="icon wb-gallery text-muted mr-1"></i>{{ $item->itens->count() }}
                        </span>

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->where('status_check', '<>',null)->count() }} verificados">
                          <i class="icon wb-check-mini mr-1 text-muted"></i>{{ $item->itens->where('status_check', '<>',null)->count() }}
                        </span>

                        @if ($item->observacoes)
                          <span class="text-nowrap align-middle font-size-12" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->observacoes }}">
                            <i class="icon wb-clipboard mr-1"></i>
                            <span class="badge badge-pill up badge-warning">!</span>
                          </span>
                        @endif

                        <small class="float-right text-muted">{{ date('d/m/Y', strtotime($item->datacad)) }}</small>
                      </p>
                    </div> <!-- end card-body -->
                  </div>
                  <!-- Task Item End -->
                @endforeach
              </div>
            </div>

            <div class="tasks border">
              <h5 class="mt-0 task-header header-title bg-blue-600 text-white">
                Preparação / Banho <span class="font-size-12 totalizador">({{ $ordens->count() }})</span>

                <br>

                <span class="font-size-14 totalizador-peso">{{ number_format($ordens->sum('peso_total_itens'), 2, ',', '.') }} g</span>
              </h5>

              <div id="task-list-os" class="task-list-items" data-plugin="kanban" data-status="F">
                @foreach ($ordens as $item)
                  <!-- Task Item -->
                  <div class="card border mb-0" data-id="{{ $item->id }}">
                    <div class="card-body p-5">
                      <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown" aria-expanded="false">
                          <i class="icon wb-more-vertical px-5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                          <div class="dropdown-divider"></div>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                        </div>
                      </div>
                      <h6 class="mt-0 mb-2 font-size-12">
                        <a href="#" data-toggle="modal" data-target="#task-detail-modal" class="text-body">{{ $item->cliente->nome ?? '' }} ({{$item->id }})</a>
                      </h6>

                      <span class="badge badge-outline badge-primary font-size-12 font-weight-500">{{ number_format($item->itens->sum('peso'), 2, ',', '.') }} g</span>

                      <!--
                      <span class="badge badge-outline badge-danger">Alta</span>
                      -->

                      <p class="mb-0 mt-4">
                        <!--
                        <img src="{{ asset('assets/portraits/0.png') }}" alt="user-img" class="avatar-xs rounded-circle mr-2" />
                        -->

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->count() }} itens">
                          <i class="icon wb-gallery text-muted mr-1"></i>{{ $item->itens->count() }}
                        </span>

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->where('status_check', '<>',null)->count() }} verificados">
                          <i class="icon wb-check-mini mr-1 text-muted"></i>{{ $item->itens->where('status_check', '<>',null)->count() }}
                        </span>

                        @if ($item->observacoes)
                          <span class="text-nowrap align-middle font-size-12" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->observacoes }}">
                            <i class="icon wb-clipboard mr-1"></i>
                            <span class="badge badge-pill up badge-warning">!</span>
                          </span>
                        @endif

                        <small class="float-right text-muted">{{ date('d/m/Y', strtotime($item->datacad)) }}</small>
                      </p>
                    </div> <!-- end card-body -->
                  </div>
                  <!-- Task Item End -->
                @endforeach
              </div>
            </div>

            <div class="tasks border">
              <h5 class="mt-0 task-header header-title bg-red-600 text-white">
                Revisão <span class="font-size-12 totalizador">({{ $revisoes->count() }})</span>

                <br>

                <span class="font-size-14 totalizador-peso">{{ number_format($revisoes->sum('peso_total_itens'), 2, ',', '.') }} g</span>
              </h5>
              <div id="task-list-rev" class="task-list-items" data-plugin="kanban" data-status="G">
                @foreach ($revisoes as $item)
                  <!-- Task Item -->
                  <div class="card border mb-0" data-id="{{ $item->id }}">
                    <div class="card-body p-5">
                      <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown" aria-expanded="false">
                          <i class="icon wb-more-vertical px-5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                          <div class="dropdown-divider"></div>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                        </div>
                      </div>
                      <h6 class="mt-0 mb-2 font-size-12">
                        <a href="#" data-toggle="modal" data-target="#task-detail-modal" class="text-body">{{ $item->cliente->nome ?? '' }} ({{$item->id }})</a>
                      </h6>

                      <span class="badge badge-outline badge-primary font-size-12 font-weight-500">{{ number_format($item->itens->sum('peso'), 2, ',', '.') }} g</span>

                      <!--
                      <span class="badge badge-outline badge-danger">Alta</span>
                      -->

                      <p class="mb-0 mt-4">
                        <!--
                        <img src="{{ asset('assets/portraits/0.png') }}" alt="user-img" class="avatar-xs rounded-circle mr-2" />
                        -->

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->count() }} itens">
                          <i class="icon wb-gallery text-muted mr-1"></i>{{ $item->itens->count() }}
                        </span>

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->where('status_check', '<>',null)->count() }} verificados">
                          <i class="icon wb-check-mini mr-1 text-muted"></i>{{ $item->itens->where('status_check', '<>',null)->count() }}
                        </span>

                        @if ($item->observacoes)
                          <span class="text-nowrap align-middle font-size-12" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->observacoes }}">
                            <i class="icon wb-clipboard mr-1"></i>
                            <span class="badge badge-pill up badge-warning">!</span>
                          </span>
                        @endif

                        <small class="float-right text-muted">{{ date('d/m/Y', strtotime($item->datacad)) }}</small>
                      </p>
                    </div> <!-- end card-body -->
                  </div>
                  <!-- Task Item End -->
                @endforeach
              </div>
            </div>

            <div class="tasks border">
              <h5 class="mt-0 task-header header-title bg-green-600 text-white">
                Peças Prontas - Expedição <span class="font-size-12 totalizador">({{ $expedicoes->count() }})</span>

                <br>

                <span class="font-size-14 totalizador-peso">{{ number_format($expedicoes->sum('peso_total_itens'), 2, ',', '.') }} g</span>
              </h5>
              <div id="task-list-exped" class="task-list-items" data-plugin="kanban" data-status="C">
                @foreach ($expedicoes as $item)
                  <!-- Task Item -->
                  <div class="card border mb-0" data-id="{{ $item->id }}">
                    <div class="card-body p-5">
                      <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown" aria-expanded="false">
                          <i class="icon wb-more-vertical px-5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                          <div class="dropdown-divider"></div>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                        </div>
                      </div>
                      <h6 class="mt-0 mb-2 font-size-12">
                        <a href="#" data-toggle="modal" data-target="#task-detail-modal" class="text-body">{{ $item->cliente->nome ?? '' }} ({{$item->id }})</a>
                      </h6>

                      <span class="badge badge-outline badge-primary font-size-12 font-weight-500">{{ number_format($item->itens->sum('peso'), 2, ',', '.') }} g</span>

                      <!--
                      <span class="badge badge-outline badge-danger">Alta</span>
                      -->

                      <p class="mb-0 mt-4">
                        <!--
                        <img src="{{ asset('assets/portraits/0.png') }}" alt="user-img" class="avatar-xs rounded-circle mr-2" />
                        -->

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->count() }} itens">
                          <i class="icon wb-gallery text-muted mr-1"></i>{{ $item->itens->count() }}
                        </span>

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->where('status_check', '<>',null)->count() }} verificados">
                          <i class="icon wb-check-mini mr-1 text-muted"></i>{{ $item->itens->where('status_check', '<>',null)->count() }}
                        </span>

                        @if ($item->observacoes)
                          <span class="text-nowrap align-middle font-size-12" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->observacoes }}">
                            <i class="icon wb-clipboard mr-1"></i>
                            <span class="badge badge-pill up badge-warning">!</span>
                          </span>
                        @endif

                        <small class="float-right text-muted">{{ date('d/m/Y', strtotime($item->datacad)) }}</small>
                      </p>
                    </div> <!-- end card-body -->
                  </div>
                  <!-- Task Item End -->
                @endforeach
              </div>
            </div>

            <div class="tasks border">
              <h5 class="mt-0 task-header header-title bg-grey-600 text-white">
                Enviado <span class="font-size-12 totalizador">({{ $concluidos->count() }})</span>

                <br>

                <span class="font-size-14 totalizador-peso">{{ number_format($concluidos->sum('peso_total_itens'), 2, ',', '.') }} g</span>
              </h5>
              <div id="task-list-exped" class="task-list-items" data-plugin="kanban" data-status="L">
                @foreach ($concluidos as $item)
                  <!-- Task Item -->
                  <div class="card border mb-0" data-id="{{ $item->id }}">
                    <div class="card-body p-5">
                      <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown" aria-expanded="false">
                          <i class="icon wb-more-vertical px-5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                          <div class="dropdown-divider"></div>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                        </div>
                      </div>
                      <h6 class="mt-0 mb-2 font-size-12">
                        <a href="#" data-toggle="modal" data-target="#task-detail-modal" class="text-body">{{ $item->cliente->nome ?? '' }} ({{$item->id }})</a>
                      </h6>

                      <span class="badge badge-outline badge-primary font-size-12 font-weight-500">{{ number_format($item->itens->sum('peso'), 2, ',', '.') }} g</span>

                      <!--
                      <span class="badge badge-outline badge-danger">Alta</span>
                      -->

                      <p class="mb-0 mt-4">
                        <!--
                        <img src="{{ asset('assets/portraits/0.png') }}" alt="user-img" class="avatar-xs rounded-circle mr-2" />
                        -->

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->count() }} itens">
                          <i class="icon wb-gallery text-muted mr-1"></i>{{ $item->itens->count() }}
                        </span>

                        <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->itens->where('status_check', '<>',null)->count() }} verificados">
                          <i class="icon wb-check-mini mr-1 text-muted"></i>{{ $item->itens->where('status_check', '<>',null)->count() }}
                        </span>

                        @if ($item->observacoes)
                          <span class="text-nowrap align-middle font-size-12" data-placement="bottom" data-toggle="tooltip" data-original-title="{{ $item->observacoes }}">
                            <i class="icon wb-clipboard mr-1"></i>
                            <span class="badge badge-pill up badge-warning">!</span>
                          </span>
                        @endif

                        <small class="float-right text-muted">{{ date('d/m/Y', strtotime($item->datacad)) }}</small>
                      </p>
                    </div> <!-- end card-body -->
                  </div>
                  <!-- Task Item End -->
                @endforeach
              </div>
            </div>

          </div>

        </div>
      </div>

    </div>
  </div>



@endsection
