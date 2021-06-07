@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/clockpicker/clockpicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/clockpicker.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/jquery-mask.js') }}"></script>
  <script src="{{ asset('assets/modules/js/retrabalhos/edit.js') }}"></script>
  <script type="text/javascript">
    coresUrl = "{{ route('materiais.cores_disponiveis', ['id' => '/']) }}/";

  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Editar Retrabalho</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('retrabalhos.update', $retrabalho->id) }}" autocomplete="off">
        @method('PUT')
        @csrf
        <div class="panel-body">

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label font-weight-400" for="idcliente">Cliente <span
                  class="text-danger">*</span></label>
              <select class="form-control" id="idcliente" name="idcliente" style="width:100%;" required>
                <option value=""></option>
                @foreach ($clientes as $cliente)
                  <option value="{{ $cliente->id }}" {{ $cliente->ativo ? '' : ' disabled' }}
                    {{ old('cliente_id', $retrabalho->cliente_id) == $cliente->id ? ' selected' : '' }}>
                    {{ $cliente->identificacao }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-2">
              <label class="form-control-label" for="data_inicio">Data Início <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio"
                name="data_inicio"
                value="{{ old('data_inicio', \Carbon\Carbon::parse($retrabalho->data_inicio)->format('d/m/Y')) }}"
                data-plugin="datepicker" data-language="pt-BR" required />
              @error('data_inicio')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-2">
              <label class="form-control-label" for="hora_inicio">Hora Início <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('hora_inicio') is-invalid @enderror" id="hora_inicio"
                name="hora_inicio"
                value="{{ old('hora_inicio', \Carbon\Carbon::parse($retrabalho->data_inicio)->format('H:i')) }}"
                data-plugin="clockpicker" data-autoclose="true" required />
              @error('hora_inicio')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-2">
              <label class="form-control-label" for="data_fim">Data Fim</label>
              <input type="text" class="form-control @error('data_fim') is-invalid @enderror" id="data_fim"
                name="data_fim" value="{{ old('data_fim') }}" data-plugin="datepicker" data-language="pt-BR" />
              @error('data_fim')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-2">
              <label class="form-control-label" for="hora_fim">Hora Fim</label>
              <input type="text" class="form-control @error('hora_fim') is-invalid @enderror" id="hora_fim"
                name="hora_fim" value="{{ old('hora_fim') }}" data-plugin="clockpicker" data-autoclose="true" />
              @error('hora_fim')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group col-md-4">
              <label class="form-control-label" for="status">Status <span class="text-danger">*</span></label>
              <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                <option value="G" {{ old('status', $retrabalho->status) == 'G' ? ' selected' : '' }}>Aguardando
                </option>
                <option value="A" {{ old('status', $retrabalho->status) == 'A' ? ' selected' : '' }}>Em Andamento
                </option>
                <option value="E" {{ old('status', $retrabalho->status) == 'E' ? ' selected' : '' }}>Concluído</option>
              </select>
              @error('status')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label font-weight-400" for="observacoes">Observações</label>
              <textarea class="form-control" name="observacoes" id="observacoes"
                rows="8">{{ old('observacoes', $retrabalho->observacoes) }}</textarea>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <table class="table table-condensed table-bordered" id="tb-item-retrabalho">
                <thead>
                  <tr>
                    <th class="w-p25">Serviço</th>
                    <th class="w-p25">Material</th>
                    <th class="w-p20">Cor</th>
                    <th class="w-p10">Ml</th>
                    <th class="w-p15">Peso</th>
                    <th class="w-p5"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($retrabalho->itens as $item)
                    <tr class="item-retrabalho" data-index="{{ $loop->index }}">
                      <td>
                        <select class="form-control" name="item_retrabalho[{{ $loop->index }}][idtiposervico]">
                          <option value=""></option>
                          @foreach ($tiposServico as $tipoServico)
                            <option value="{{ $tipoServico->id }}"
                              {{ $item->tiposervico_id == $tipoServico->id ? ' selected' : '' }}>
                              {{ $tipoServico->descricao }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select class="form-control" name="item_retrabalho[{{ $loop->index }}][idmaterial]">
                          <option value=""></option>
                          @foreach ($materiais as $material)
                            <option value="{{ $material->id }}"
                              {{ $item->material_id == $material->id ? ' selected' : '' }}>
                              {{ $material->descricao }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select class="form-control" name="item_retrabalho[{{ $loop->index }}][idcor]">
                          <option value=""></option>
                          @foreach ($item->material->cores as $cor)
                            <option value="{{ $cor->id }}"
                              {{ $item->cor_id == $cor->id ? ' selected' : '' }}>
                              {{ $cor->descricao }}
                            </option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control" name="item_retrabalho[{{ $loop->index }}][milesimos]"
                          min="0" value="{{ $item->milesimos }}" />
                      </td>
                      <td>
                        <input type="number" class="form-control" name="item_retrabalho[{{ $loop->index }}][peso]"
                          min="0" value="{{ number_format($item->peso, 0) }}" />
                      </td>
                      <td>
                        <input type="hidden" name="item_retrabalho[{{ $loop->index }}][item_id]"
                          value="{{ $item->id }}">
                        <div class="item-retrabalho-controls d-flex justify-content-center">
                          <button type="button" class="btn btn-sm btn-block btn-outline-danger btn-remove-item-retrabalho"
                            title="Excluir"><i class="fa fa-times"></i></button>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  <tr class="item-retrabalho" data-index="{{ $retrabalho->itens->count() }}">
                    <td>
                      <select class="form-control"
                        name="item_retrabalho[{{ $retrabalho->itens->count() }}][idtiposervico]">
                        <option value=""></option>
                        @foreach ($tiposServico as $tipoServico)
                          <option value="{{ $tipoServico->id }}">{{ $tipoServico->descricao }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <select class="form-control"
                        name="item_retrabalho[{{ $retrabalho->itens->count() }}][idmaterial]">
                        <option value=""></option>
                        @foreach ($materiais as $material)
                          <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <select class="form-control" name="item_retrabalho[{{ $retrabalho->itens->count() }}][idcor]">
                        <option value=""></option>
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control"
                        name="item_retrabalho[{{ $retrabalho->itens->count() }}][milesimos]" min="0" />
                    </td>
                    <td>
                      <input type="number" class="form-control"
                        name="item_retrabalho[{{ $retrabalho->itens->count() }}][peso]" min="0" />
                    </td>
                    <td>
                      <input type="hidden" name="item_retrabalho[{{ $retrabalho->itens->count() }}][item_id]">
                      <div class="item-retrabalho-controls d-none justify-content-center">
                        <button type="button" class="btn btn-sm btn-block btn-outline-danger btn-remove-item-retrabalho"
                          title="Excluir"><i class="fa fa-times"></i></button>
                      </div>
                      <button type="button" class="btn btn-sm btn-block btn-info btn-add-item-retrabalho"
                        title="Adicionar item"><i class="icon wb-plus"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('retrabalhos.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
