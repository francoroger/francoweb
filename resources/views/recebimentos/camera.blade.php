<!-- Modal -->
<div class="modal fade" id="capturaModal" tabindex="-1" role="dialog" aria-labelledby="capturaModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="capturaModalLabel">Capturar Imagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="capture_cam" data-url="{{ route('recebimentos.upload') }}" data-token="{{ csrf_token() }}">

        <div class="live-cam"></div>
        <figure class="overlay">
          <img class="card-img-top img-fluid w-full overlay-figure preview-img" src="{{ asset('assets/photos/placeholder.png') }}" alt="Preview" />
          <div class="overlay-panel overlay-icon overlay-background">
            <i class="icon wb-image"></i>
          </div>
        </figure>

        <button type="button" id="btn-enable-cam" class="d-none" data-action="camera-toggle-enable"></button>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn-capturar">Tirar Foto</button>
      </div>
    </div>
  </div>
</div>
