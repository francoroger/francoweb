<div class="row">
  @foreach ($tanques as $tanque)
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            {{ $tanque->descricao }}
            <small> a cada {{ $tanque->ciclo_reforco }} g</small>
          </h3>
        </div>
        <div class="panel-body">
          <div class="gauge gauge-sm" id="{{ "tanque-" . $tanque->id }}" data-plugin="gauge" data-value="{{ $tanque->ciclos->where('status', 'P')->sum('peso') }}" data-max-value="{{ $tanque->ciclo_reforco }}">
            <div class="gauge-label"></div>
            <canvas width="150" height="110"></canvas>
          </div>
        </div>
        <div class="panel-footer text-center">
          <button type="button" data-id="{{ $tanque->id }}" class="btn btn-primary btn-reforco">Reforço</button>
        </div>
      </div>
    </div>
  @endforeach
</div>
