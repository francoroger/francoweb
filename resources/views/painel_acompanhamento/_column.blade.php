<div class="tasks border">
  <div class="mt-0 task-header {{ $bg_color }} {{ $text_color }}">
    <div class="font-size-12 float-right totalizador">({{ $data->count() }})</div>
    <div class="example-title text-truncate font-weight-500">{{ $label }}</div>
    <div class="font-size-14 totalizador-peso">
      @if ($data->sum('peso') < 1000)
        {{ number_format($data->sum('peso'), 0, ',', '.') }} g
      @else
        {{ number_format($data->sum('peso') / 1000, 2, ',', '.') }} Kg
      @endif
    </div>
  </div>
  <div class="h-calculated" data-plugin="scrollable">
    <div data-role="container">
      <div data-role="content">
        <div id="task-list-catalog" class="task-list-items" data-plugin="kanban" data-multi-drag="{{ $multi_drag }}"
          data-status="{{ $status }}">
          @foreach ($data as $item)
            <div class="card border mb-0" data-id="{{ $item->id }}">
              <div class="card-body p-5">
                @if ($status == 'R' || ($status == 'S' && $item->substatus == 'A') || ($status == 'T' && $item->substatus == 'A') || ($status == 'F' && $item->substatus == 'G') || ($status == 'T' && $item->substatus == 'G') || ($status == 'C' && $item->substatus == 'G'))
                  <div class="dropdown float-right">
                    <a href="#" class="dropdown-toggle text-muted arrow-none" data-toggle="dropdown"
                      aria-expanded="false">
                      <i class="icon wb-more-vertical px-5"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      @if ($status == 'R')
                        <a href="javascript:void(0);" class="dropdown-item font-weight-400 action-arquivar"><i
                            class="fa fa-folder-open mr-2"></i>Arquivar</a>
                      @endif
                      @if ($status == 'S' && $item->substatus == 'A')
                        <a href="javascript:void(0);"
                          class="dropdown-item text-danger font-weight-400 action-encerrar-sep"><i
                            class="fa fa-check mr-2"></i>Encerrar</a>
                      @endif
                      @if ($status == 'T' && $item->substatus == 'A')
                        <a href="javascript:void(0);"
                          class="dropdown-item text-danger font-weight-400 action-encerrar-retrab"><i
                            class="fa fa-check mr-2"></i>Encerrar</a>
                      @endif
                      @if ($status == 'F' && $item->substatus == 'G')
                        <a href="javascript:void(0);"
                          class="dropdown-item text-primary font-weight-400 action-iniciar-prep"><i
                            class="fa fa-clock-o mr-2"></i>Iniciar</a>
                      @endif
                      @if ($status == 'T' && $item->substatus == 'G')
                        <a href="javascript:void(0);"
                          class="dropdown-item text-primary font-weight-400 action-iniciar-retrab"><i
                            class="fa fa-clock-o mr-2"></i>Iniciar</a>
                      @endif
                      @if ($status == 'C' && $item->substatus == 'G')
                        <a href="javascript:void(0);"
                          class="dropdown-item text-primary font-weight-400 action-iniciar-exped"><i
                            class="fa fa-clock-o mr-2"></i>Iniciar</a>
                      @endif
                      @if ($label === 'Retrabalho')
                        <a href="javascript:void(0);" class="dropdown-item edit-retrabalho"><i
                            class="icon wb-edit mr-2"></i>Editar</a>
                      @endif
                      <!--
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#task-detail-modal" class="dropdown-item"><i class="icon wb-search mr-2"></i>Visualizar</a>
                  <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-edit mr-2"></i>Editar</a>
                  <a href="javascript:void(0);" class="dropdown-item"><i class="icon wb-print mr-2"></i>Imprimir</a>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0);" class="dropdown-item text-danger"><i class="icon wb-trash mr-2"></i>Excluir</a>
                  -->
                    </div>
                  </div>
                @endif
                <h6 class="mt-0 mb-0 font-size-12">
                  <a href="javascript:void(0);" class="text-body">{{ $item->cliente }}</a>
                </h6>
                <span class="d-block mb-2 font-size-10">#{{ $item->id }}</span>
                <span class="badge badge-outline badge-primary badge-peso font-size-12 font-weight-500 mt-5"
                  data-placement="right" data-toggle="tooltip"
                  data-original-title="{{ number_format($item->peso_real, 0, ',', '.') }} g">
                  {{ number_format($item->peso, 0, ',', '.') }} g
                </span>
                @if ($item->substatus == 'G')
                  <span class="badge badge badge-danger font-size-12 font-weight-500 ml-5">Aguardando</span>
                @endif
                @if ($item->substatus == 'A')
                  <span class="badge badge badge-warning font-size-12 font-weight-500 ml-5">Em Andamento</span>
                @endif
                @if ($item->substatus == 'E')
                  <span class="badge badge badge-success font-size-12 font-weight-500 ml-5">Concluído</span>
                @endif
                <ul class="blocks-2 mb-0 mt-4">
                  <li class="mb-0">
                    <div class="example-col">
                      <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom"
                        data-toggle="tooltip" data-original-title="{{ $item->qtde_itens }} itens">
                        <i class="icon wb-gallery text-muted mr-1"></i>{{ $item->qtde_itens }}
                      </span>
                      <span class="text-nowrap align-middle font-size-12 mr-2" data-placement="bottom"
                        data-toggle="tooltip" data-original-title="{{ $item->qtde_check }} verificados">
                        <i class="icon wb-check-mini mr-1 text-muted"></i>{{ $item->qtde_check }}
                      </span>
                      @if ($item->obs)
                        <span class="text-nowrap align-middle font-size-12" data-placement="bottom"
                          data-toggle="tooltip" data-original-title="{{ $item->obs }}">
                          <i class="icon wb-clipboard mr-1"></i>
                          <span class="badge badge-pill up badge-warning">!</span>
                        </span>
                      @endif
                    </div>
                  </li>
                  <li class="mb-0">
                    <div class="example-col text-right" style="line-height: 1;">
                      <small class="text-body d-block" data-toggle="tooltip"
                        data-original-title="{{ $item->data_carbon ? $item->data_carbon->format('d/m/Y H:i:s') : '' }}"><strong>{{ $item->data_carbon ? 'Etapa: ' . $item->data_carbon->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : '' }}</strong></small>
                      <small class="text-body" data-toggle="tooltip"
                        data-original-title="{{ $item->data_inicio ? $item->data_inicio->format('d/m/Y H:i:s') : '' }}">{{ $item->data_inicio ? 'Total: ' . $item->data_inicio->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 3) : '' }}</small>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
