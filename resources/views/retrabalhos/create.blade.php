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
      <h1 class="page-title font-size-26 font-weight-100">Novo Retrabalho</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('retrabalhos.store') }}" autocomplete="off">
        @csrf
        <div class="panel-body">

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label font-weight-400" for="idcliente">Cliente <span
                  class="text-danger">*</span></label>
              <select class="form-control" id="idcliente" name="idcliente" style="width:100%;" required>
                <option value=""></option>
                @foreach ($clientes as $cliente)
                  <option value="{{ $cliente->id }}" {{ $cliente->ativo ? '' : ' disabled' }}>
                    {{ $cliente->identificacao }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-2">
              <label class="form-control-label" for="data_inicio">Data Início <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio"
                name="data_inicio" value="{{ old('data_inicio', \Carbon\Carbon::now()->format('d/m/Y')) }}"
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
                name="hora_inicio" value="{{ old('hora_inicio', \Carbon\Carbon::now()->format('H:i')) }}"
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
                <option value="G">Aguardando</option>
                <option value="A">Em Andamento</option>
                <option value="E">Concluído</option>
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
              <textarea class="form-control" name="observacoes" id="observacoes" rows="8"></textarea>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <table class="table table-condensed table-bordered" id="tb-item-retrabalho">
                <thead>
                  <tr>
                    <th class="w-p20">Serviço</th>
                    <th class="w-p20">Material</th>
                    <th class="w-p15">Cor</th>
                    <th class="w-p10">Ml</th>
                    <th class="w-p10">Peso</th>
                    <th class="w-p20">Tipo de Falha</th>
                    <th class="w-p5"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="item-retrabalho" data-index="0">
                    <td>
                      <select class="form-control" name="item_retrabalho[0][idtiposervico]">
                        <option value=""></option>
                        @foreach ($tiposServico as $tipoServico)
                          <option value="{{ $tipoServico->id }}">{{ $tipoServico->descricao }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <select class="form-control" name="item_retrabalho[0][idmaterial]">
                        <option value=""></option>
                        @foreach ($materiais as $material)
                          <option value="{{ $material->id }}">{{ $material->descricao }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <select class="form-control" name="item_retrabalho[0][idcor]">
                        <option value=""></option>
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control" name="item_retrabalho[0][milesimos]" min="0" />
                    </td>
                    <td>
                      <input type="number" class="form-control" name="item_retrabalho[0][peso]" min="0" />
                    </td>
                    <td>
                      <select class="form-control" name="item_retrabalho[0][tipo_falha_id]">
                        <option value=""></option>
                        @foreach ($tiposFalha as $tipoFalha)
                          <option value="{{ $tipoFalha->id }}">{{ $tipoFalha->descricao }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="hidden" name="item_retrabalho[0][item_id]">
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
