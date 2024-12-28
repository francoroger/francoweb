{{ Log::info('Iniciando renderização de data.blade.php') }}
<div class="row">
  @foreach ($tanques as $tanque)
    {{ Log::info('Renderizando tanque ' . $tanque->id) }}
    <div class="col-md-4">
      <div id="{{ 'pnl-' . $tanque->id }}"
        class="panel border{{ $tanque->excedeu ? ' panel-danger border-danger' : '' }}">
        <div class="panel-heading text-center">
          <h3 class="panel-title">
            {{ $tanque->descricao }}
            <span
              class="panel-desc{{ $tanque->excedeu ? ' text-white' : '' }}">a
              cada {{ $tanque->ciclo_reforco }} g</span>
          </h3>
          <div class="panel-actions panel-actions-keep" style="top: 18px; right: 0px;">
            <div class="dropdown">
              <a class="panel-action" data-toggle="dropdown" href="#"><i class="fa fa-sort-down"></i></a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="desfazer_reforco dropdown-item" href="#" data-id="{{ $tanque->id }}"><i
                    class="fa fa-undo"></i> Defazer último reforço</a>

                <div class="dropdown-divider"></div>
                <a class="reforco_analise dropdown-item" href="#" data-id="{{ $tanque->id }}"><i
                    class="fa fa-flask"></i> Reforço por análise</a>
                <a class="reforco_comentario dropdown-item" href="#" data-id="{{ $tanque->id }}"><i
                    class="fa fa-comment-o"></i> Adicionar observação</a>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body text-center">
          {{ Log::info('Renderizando gauge para tanque ' . $tanque->id) }}
          <div class="gauge mt-30" id="{{ 'tanque-' . $tanque->id }}" data-plugin="gauge" data-color-start="#FFDBDC"
            data-color-stop="#E62020" data-angle="0" data-generate-gradient="true"
            data-value="{{ $tanque->soma_peso }}"
            data-max-value="{{ $tanque->ciclo_reforco }}">
            <div class="gauge-label"></div>
            <canvas width="250" height="150"></canvas>
          </div>
          <span id="{{ 'excedente-' . $tanque->id }}"
            class="font-size-12 font-weight-500 text-danger">{!! $tanque->excedeu ? 'Excedeu ' . $tanque->diferenca . ' g' : '&nbsp;' !!}</span>
        </div>
        <div class="panel-footer text-center">
          <button type="button" data-id="{{ $tanque->id }}" data-descricao="{{ $tanque->descricao }}"
            class="btn btn-success btn-reforco"><i class="fa fa-flask"></i> Reforçar</button>
        </div>
      </div>
    </div>
    {{ Log::info('Tanque ' . $tanque->id . ' renderizado com sucesso') }}
  @endforeach
</div>
{{ Log::info('Renderização de data.blade.php finalizada') }}
