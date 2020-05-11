@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/modules/css/recebimentos/gallery.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/clockpicker/clockpicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <!-- Plugin webcamjs -->
  <script src="{{ asset('assets/plugins/webcamjs/webcam.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/clockpicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <!-- Plugin Camera -->
  <script src="{{ asset('assets/js/Plugin/camera.js') }}"></script>
  <!-- Exemplo Camera -->
  <script src="{{ asset('assets/modules/js/recebimentos/camera.js') }}"></script>

  <script>
    //Evento remover foto
    $(document).on('click', '.btn-delete', function(event) {
      event.preventDefault();
      if (confirm('Deseja realmente excluir a foto?')) {
        var elem = $(this).parent().parent().parent().parent().parent();
        var id = $(this).data('id');
        var token = "{{ csrf_token() }}";
        var route = "{{ route('recebimentos.destroyFoto', '') }}/" + id;

        if (id) {
          $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'DELETE',
            dataType: "json",
            success: function (data)
            {
              elem.remove();
            }
          });
        } else {
          elem.remove();
        }
      }
    });
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Editar Recebimento</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('recebimentos.update', $recebimento->id) }}" autocomplete="off">
        <input type="hidden" name="_method" value="PUT">
        @csrf
        <div class="panel-body">
          <div class="row">
            <div class="form-group col-md-2">
              <label class="form-control-label" for="data_receb">Data de Entrada <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('data_receb') is-invalid @enderror" id="data_receb" name="data_receb" value="{{ old('data_receb', date('d/m/Y', strtotime($recebimento->data_receb))) }}" data-plugin="datepicker" data-language="pt-BR" required />
              @error('data_receb')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-2">
              <label class="form-control-label" for="hora_receb">Hora de Entrada <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('hora_receb') is-invalid @enderror" id="hora_receb" name="hora_receb" value="{{ old('hora_receb', date('H:i', strtotime($recebimento->hora_receb))) }}" data-plugin="clockpicker" data-autoclose="true" required />
              @error('hora_receb')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-8">
              <label class="form-control-label" for="idresp">Entregue por</label>
              <select class="form-control @error('idresp') is-invalid @enderror" id="idresp" name="idresp" data-plugin="select2">
                <option value=""></option>
                @foreach ($responsaveis as $resp)
                  <option value="{{ $resp->id }}"{{ old('idresp', $recebimento->idresp) == $resp->id ? ' selected' : '' }}>{{ $resp->descricao }}</option>
                @endforeach
              </select>
              @error('idresp')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="idcliente">Cliente <span class="text-danger">*</span></label>
              <select class="form-control @error('idcliente') is-invalid @enderror" id="idcliente" name="idcliente" data-plugin="select2" required>
                <option value=""></option>
                @foreach ($clientes as $cliente)
                  <option value="{{ $cliente->id }}"{{ old('idcliente', $recebimento->idcliente) == $cliente->id ? ' selected' : '' }}{{ $cliente->ativo ? '' : ' disabled' }}>{{ $cliente->identificacao }}</option>
                @endforeach
              </select>
              @error('idcliente')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-4">
              <label class="form-control-label" for="pesototal">Peso Total</label>
              <div class="input-group">
                <input type="text" class="form-control @error('pesototal') is-invalid @enderror" id="pesototal" name="pesototal" value="{{ old('pesototal', $recebimento->pesototal) }}" />
                <span class="input-group-addon">
                  gramas
                </span>
              </div>
              @error('pesototal')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-8">
              <label class="form-control-label" for="idfornec">Fornecedor</label>
              <select class="form-control @error('idfornec') is-invalid @enderror" id="idfornec" name="idfornec" data-plugin="select2">
                <option value=""></option>
                @foreach ($fornecedores as $fornec)
                  <option value="{{ $fornec->id }}"{{ old('idfornec', $recebimento->idfornec) == $fornec->id ? ' selected' : '' }}{{ $fornec->ativo ? '' : ' disabled' }}>{{ $fornec->nome }}</option>
                @endforeach
              </select>
              @error('idfornec')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="obs">Observações</label>
              <textarea class="form-control @error('obs') is-invalid @enderror" id="obs" name="obs" rows="5" placeholder="Observações">{{ old('obs', $recebimento->obs) }}</textarea>
              @error('obs')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <h4 class="example-title">Fotos</h4>

          <div class="recebimento-gallery">
            <ul class="blocks blocks-100 blocks-xxl-5 blocks-lg-4 blocks-md-3 blocks-sm-2" id="foto-container">
              @foreach ($recebimento->fotos as $foto)
              <li>
                <div class="panel">
                  <figure class="overlay overlay-hover animation-hover">
                    <img class="caption-figure overlay-figure" src="{{ asset("fotos/{$foto->foto}") }}">
                    <input type="hidden" name="fotos[]" value="{{ $foto->foto }}">
                    <figcaption class="overlay-panel overlay-background overlay-fade text-center vertical-align">
                      <div class="btn-group">
                        <button type="button" class="btn btn-icon btn-pure btn-default btn-delete" title="Excluir" data-id="{{ $foto->id }}">
                          <i class="icon wb-trash"></i>
                        </button>
                      </div>
                    </figcaption>
                  </figure>
                </div>
              </li>
              @endforeach
            </ul>
          </div>

          <div class="row">
            <div class="col-md-3">
              <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#capturaModal">
                <i class="fa fa-camera mr-5"></i> Adicionar Foto
              </button>
            </div>
          </div>

          @include('recebimentos.camera')

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('recebimentos.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @endsection
