<div class="row">
  @foreach ($tanques as $tanque)
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            {{ $tanque->descricao }}
            <small> a cada {{ $tanque->ciclo_reforco }} g</small>
          </h3>
          <div class="panel-actions panel-actions-keep">
            <div class="dropdown">
              <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#"
                aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
              <div class="dropdown-menu dropdown-menu-bullet dropdown-menu-right" aria-labelledby="examplePanelDropdown"
                role="menu">
                <a class="desfazer_reforco dropdown-item" href="#" data-id="{{ $tanque->id }}" role="menuitem"><i class="fa fa-undo" aria-hidden="true"></i> Defazer último reforço</a>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body text-center">
          <div class="gauge gauge-sm" id="{{ "tanque-" . $tanque->id }}" data-plugin="gauge" data-value="{{ $tanque->ciclos->where('status', 'P')->sum('peso') }}" data-max-value="{{ $tanque->ciclo_reforco }}">
            <div class="gauge-label"></div>
            <canvas width="150" height="110"></canvas>
          </div>
          <span id="{{ "excedente-" . $tanque->id }}" class="font-size-10 text-danger">{!! $tanque->ciclos->where('status', 'P')->sum('peso') > $tanque->ciclo_reforco ? "Excedeu " . ($tanque->ciclos->where('status', 'P')->sum('peso') - $tanque->ciclo_reforco) . ' g'  : "&nbsp;" !!}</span>
        </div>
        <div class="panel-footer text-center">
          <button type="button" data-id="{{ $tanque->id }}" data-descricao="{{ $tanque->descricao }}" class="btn btn-success btn-reforco"><i class="fa fa-flask"></i> Reforço</button>
        </div>
      </div>
    </div>
  @endforeach
</div>
