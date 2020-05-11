<div class="tasks border">
  <div class="mt-0 task-header {{ $bg_color }} {{ $text_color }}">
    <div class="font-size-12 float-right totalizador">({{ $data->count() }})</div>
    <div class="example-title text-truncate font-weight-500">{{ $label }}</div>
    <div class="font-size-14 totalizador-peso">
      @if ($data->sum('peso_total_itens') < 1000)
        {{ number_format($data->sum('peso_total_itens'), 2, ',', '.') }} g 
      @else
        {{ number_format($data->sum('peso_total_itens')/1000, 2, ',', '.') }} Kg 
      @endif 
    </div> 
  </div> 
  <div class="h-450" data-plugin="scrollable">
    <div data-role="container">
      <div data-role="content">
        <div id="task-list-catalog" class="task-list-items" data-plugin="kanban" data-status="A">
          @foreach ($data as $item)
          <div class="card border mb-0" data-id="{{ $item->id }}">
            <div class="card-body p-5">
              <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown" aria-expanded="false">
                  <i class="icon wb-more-vertical px-5"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                  <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                  <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                </div>
              </div>
              <h6 class="mt-0 mb-2 font-size-12">
                <a href="#" data-toggle="modal" data-target="#task-detail-modal"
                  class="text-body">{{ $item->cliente->nome ?? '' }} ({{$item->id }})</a>
              </h6>
              <span class="badge badge-outline badge-primary font-size-12 font-weight-500">{{ number_format($item->itens->sum('peso_real'), 0, ',', '.') }} g</span>
              <p class="mb-0 mt-4">
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
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
