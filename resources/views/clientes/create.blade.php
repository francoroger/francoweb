@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
  <script src="{{ asset('assets/js/Plugin/jquery-mask.js') }}"></script>
  <script src="{{ asset('assets/modules/js/clientes/edit.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <form class="panel" method="post" action="{{ route('clientes.store') }}" autocomplete="off">
      @csrf
      <div class="page-header">
        <h1 class="page-title font-size-26 font-weight-100">Novo Cliente</h1>
        <div class="page-header-actions">
          <div class="float-left mr-10">
            <input type="hidden" name="ativo" value="0">
            <input type="checkbox" id="ativo" name="ativo" data-plugin="switchery" value="1" {{ old('ativo', 1) == 1 ? 'checked' : '' }} />
          </div>
          <label class="pt-3" for="ativo">Ativo</label>
        </div>
      </div>

      <div class="page-content">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-principal" aria-controls="tab-principal" role="tab" aria-expanded="true">Dados Cadastrais</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-endereco" aria-controls="tab-endereco" role="tab">Endereço</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-obs" aria-controls="tab-obs" role="tab">Observações</a></li>
        </ul>
        <div class="panel-body container-fluid">
          <div class="tab-content">
            <div class="tab-pane active" id="tab-principal" role="tabpanel">
              <div class="row">
                <div class="form-group col-md-9">
                  <label class="form-control-label" for="nome">Nome <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" placeholder="Nome / Apelido" value="{{ old('nome') }}" required />
                  @error('nome')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-3">
                  <label class="form-control-label">Pessoa <span class="text-danger">*</span></label>
                  <div>
                    <div class="radio-custom radio-default radio-inline">
                      <input type="radio" id="pessoa_fisica" name="tipopessoa" value="F" {{ old('tipopessoa') == 'F' ? 'checked' : '' }} />
                      <label for="pessoa_fisica">Física</label>
                    </div>
                    <div class="radio-custom radio-default radio-inline">
                      <input type="radio" id="pessoa_juridica" name="tipopessoa" value="J" {{ old('tipopessoa') == 'J' ? 'checked' : '' }} />
                      <label for="pessoa_juridica">Jurídica</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-3">
                  <label class="form-control-label" for="cpf">CPF / CNPJ <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" placeholder="CPF / CNPJ" value="{{ old('cpf') }}" required />
                  @error('cpf')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-9">
                  <label class="form-control-label" for="rzsc">Razão Social</label>
                  <input type="text" class="form-control @error('rzsc') is-invalid @enderror" id="rzsc" name="rzsc" placeholder="Razão Social / Nome Completo" value="{{ old('rzsc') }}" />
                  @error('rzsc')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-3">
                  <label class="form-control-label" for="inscest">Insc. Estadual / RG</label>
                  <input type="text" class="form-control @error('inscest') is-invalid @enderror" id="inscest" name="inscest" placeholder="Inscrição Estadual / RG" value="{{ old('inscest') }}" />
                  @error('inscest')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-9">
                  <label class="form-control-label" for="idguia">Guia</label>
                  <select class="form-control @error('idguia') is-invalid @enderror" id="idguia" name="idguia" data-plugin="select2">
                    <option value=""></option>
                    @foreach ($guias as $guia)
                      <option value="{{ $guia->id }}"{{ old('idguia') == $guia->id ? ' selected' : '' }}{{ $guia->ativo ? '' : ' disabled' }}>{{ $guia->nome }}</option>
                    @endforeach
                  </select>
                  @error('idguia')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-3">
                  <label class="form-control-label" for="telefone">Telefone</label>
                  <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" placeholder="Telefone Principal" value="{{ old('telefone') }}" data-plugin="mask" data-type="cellphone" />
                  @error('telefone')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-3">
                  <label class="form-control-label" for="celular">Celular <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" placeholder="Celular Principal" value="{{ old('celular') }}" data-plugin="mask" data-type="cellphone" required />
                  @error('celular')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-6">
                  <label class="form-control-label" for="email">E-mail <span class="text-danger">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail Principal" value="{{ old('email') }}" required />
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-3">
                  <input type="text" class="form-control @error('telefone2') is-invalid @enderror" id="telefone2" name="telefone2" placeholder="Telefone Secundário" value="{{ old('telefone2') }}" data-plugin="mask" data-type="cellphone" />
                  @error('telefone2')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-3">
                  <input type="text" class="form-control @error('celular2') is-invalid @enderror" id="celular2" name="celular2" placeholder="Celular Secundário" value="{{ old('celular2') }}" data-plugin="mask" data-type="cellphone" />
                  @error('celular2')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-6">
                  <input type="email" class="form-control @error('email2') is-invalid @enderror" id="email2" name="email2" placeholder="E-mail Secundário" value="{{ old('email2') }}" />
                  @error('email2')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-3">
                  <input type="text" class="form-control @error('telefone3') is-invalid @enderror" id="telefone3" name="telefone3" placeholder="Telefone Alternativo" value="{{ old('telefone3') }}" data-plugin="mask" data-type="cellphone" />
                  @error('telefone3')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab-endereco" role="tabpanel">

              <h4 class="example-title">Endereço Principal</h4>

              <div class="row">
                <div class="form-group col-md-3">
                  <label class="form-control-label" for="cep">CEP</label>
                  <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" placeholder="CEP" value="{{ old('cep') }}" data-plugin="mask" data-pattern="00000-000" />
                  @error('cep')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-9">
                  <label class="form-control-label" for="endereco">Endereço</label>
                  <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" placeholder="Endereço" value="{{ old('endereco') }}" />
                  @error('endereco')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-5">
                  <label class="form-control-label" for="bairro">Bairro</label>
                  <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" placeholder="Bairro" value="{{ old('bairro') }}" />
                  @error('bairro')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-5">
                  <label class="form-control-label" for="cidade">Cidade</label>
                  <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" placeholder="Cidade" value="{{ old('cidade') }}" />
                  @error('cidade')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-2">
                  <label class="form-control-label" for="uf">UF</label>
                  <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                    <option value=""></option>
                    <option value="AC"{{ old('uf') == 'AC' ? ' selected' : '' }}>AC</option>
                    <option value="AL"{{ old('uf') == 'AL' ? ' selected' : '' }}>AL</option>
                    <option value="AP"{{ old('uf') == 'AP' ? ' selected' : '' }}>AP</option>
                    <option value="AM"{{ old('uf') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="BA"{{ old('uf') == 'BA' ? ' selected' : '' }}>BA</option>
                    <option value="CE"{{ old('uf') == 'CE' ? ' selected' : '' }}>CE</option>
                    <option value="DF"{{ old('uf') == 'DF' ? ' selected' : '' }}>DF</option>
                    <option value="ES"{{ old('uf') == 'ES' ? ' selected' : '' }}>ES</option>
                    <option value="GO"{{ old('uf') == 'GO' ? ' selected' : '' }}>GO</option>
                    <option value="MA"{{ old('uf') == 'MA' ? ' selected' : '' }}>MA</option>
                    <option value="MT"{{ old('uf') == 'MT' ? ' selected' : '' }}>MT</option>
                    <option value="MS"{{ old('uf') == 'MS' ? ' selected' : '' }}>MS</option>
                    <option value="MG"{{ old('uf') == 'MG' ? ' selected' : '' }}>MG</option>
                    <option value="PA"{{ old('uf') == 'PA' ? ' selected' : '' }}>PA</option>
                    <option value="PB"{{ old('uf') == 'PB' ? ' selected' : '' }}>PB</option>
                    <option value="PR"{{ old('uf') == 'PR' ? ' selected' : '' }}>PR</option>
                    <option value="PE"{{ old('uf') == 'PE' ? ' selected' : '' }}>PE</option>
                    <option value="PI"{{ old('uf') == 'PI' ? ' selected' : '' }}>PI</option>
                    <option value="RJ"{{ old('uf') == 'RJ' ? ' selected' : '' }}>RJ</option>
                    <option value="RN"{{ old('uf') == 'RN' ? ' selected' : '' }}>RN</option>
                    <option value="RS"{{ old('uf') == 'RS' ? ' selected' : '' }}>RS</option>
                    <option value="RO"{{ old('uf') == 'RO' ? ' selected' : '' }}>RO</option>
                    <option value="RR"{{ old('uf') == 'RR' ? ' selected' : '' }}>RR</option>
                    <option value="SC"{{ old('uf') == 'SC' ? ' selected' : '' }}>SC</option>
                    <option value="SP"{{ old('uf') == 'SP' ? ' selected' : '' }}>SP</option>
                    <option value="SE"{{ old('uf') == 'SE' ? ' selected' : '' }}>SE</option>
                    <option value="TO"{{ old('uf') == 'TO' ? ' selected' : '' }}>TO</option>
                  </select>
                  @error('uf')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <h4 class="example-title">Endereço de Entrega</h4>

              <div class="row">
                <div class="form-group col-md-3">
                  <label class="form-control-label" for="cep_entrega">CEP</label>
                  <input type="text" class="form-control @error('cep_entrega') is-invalid @enderror" id="cep_entrega" name="cep_entrega" placeholder="CEP" value="{{ old('cep_entrega') }}" data-plugin="mask" data-pattern="00000-000" />@error('cep_entrega')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-9">
                  <label class="form-control-label" for="endereco_entrega">Endereço</label>
                  <input type="text" class="form-control @error('endereco_entrega') is-invalid @enderror" id="endereco_entrega" name="endereco_entrega" placeholder="Endereço" value="{{ old('endereco_entrega') }}" />
                  @error('endereco_entrega')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-5">
                  <label class="form-control-label" for="bairro_entrega">Bairro</label>
                  <input type="text" class="form-control @error('bairro_entrega') is-invalid @enderror" id="bairro_entrega" name="bairro_entrega" placeholder="Bairro" value="{{ old('bairro_entrega') }}" />
                  @error('bairro_entrega')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-5">
                  <label class="form-control-label" for="cidade_entrega">Cidade</label>
                  <input type="text" class="form-control @error('cidade_entrega') is-invalid @enderror" id="cidade_entrega" name="cidade_entrega" placeholder="Cidade" value="{{ old('cidade_entrega') }}" />
                  @error('cidade_entrega')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-2">
                  <label class="form-control-label" for="uf_entrega">UF</label>
                  <select class="form-control @error('uf_entrega') is-invalid @enderror" id="uf_entrega" name="uf_entrega">
                    <option value=""></option>
                    <option value="AC"{{ old('uf_entrega') == 'AC' ? ' selected' : '' }}>AC</option>
                    <option value="AL"{{ old('uf_entrega') == 'AL' ? ' selected' : '' }}>AL</option>
                    <option value="AP"{{ old('uf_entrega') == 'AP' ? ' selected' : '' }}>AP</option>
                    <option value="AM"{{ old('uf_entrega') == 'AM' ? ' selected' : '' }}>AM</option>
                    <option value="BA"{{ old('uf_entrega') == 'BA' ? ' selected' : '' }}>BA</option>
                    <option value="CE"{{ old('uf_entrega') == 'CE' ? ' selected' : '' }}>CE</option>
                    <option value="DF"{{ old('uf_entrega') == 'DF' ? ' selected' : '' }}>DF</option>
                    <option value="ES"{{ old('uf_entrega') == 'ES' ? ' selected' : '' }}>ES</option>
                    <option value="GO"{{ old('uf_entrega') == 'GO' ? ' selected' : '' }}>GO</option>
                    <option value="MA"{{ old('uf_entrega') == 'MA' ? ' selected' : '' }}>MA</option>
                    <option value="MT"{{ old('uf_entrega') == 'MT' ? ' selected' : '' }}>MT</option>
                    <option value="MS"{{ old('uf_entrega') == 'MS' ? ' selected' : '' }}>MS</option>
                    <option value="MG"{{ old('uf_entrega') == 'MG' ? ' selected' : '' }}>MG</option>
                    <option value="PA"{{ old('uf_entrega') == 'PA' ? ' selected' : '' }}>PA</option>
                    <option value="PB"{{ old('uf_entrega') == 'PB' ? ' selected' : '' }}>PB</option>
                    <option value="PR"{{ old('uf_entrega') == 'PR' ? ' selected' : '' }}>PR</option>
                    <option value="PE"{{ old('uf_entrega') == 'PE' ? ' selected' : '' }}>PE</option>
                    <option value="PI"{{ old('uf_entrega') == 'PI' ? ' selected' : '' }}>PI</option>
                    <option value="RJ"{{ old('uf_entrega') == 'RJ' ? ' selected' : '' }}>RJ</option>
                    <option value="RN"{{ old('uf_entrega') == 'RN' ? ' selected' : '' }}>RN</option>
                    <option value="RS"{{ old('uf_entrega') == 'RS' ? ' selected' : '' }}>RS</option>
                    <option value="RO"{{ old('uf_entrega') == 'RO' ? ' selected' : '' }}>RO</option>
                    <option value="RR"{{ old('uf_entrega') == 'RR' ? ' selected' : '' }}>RR</option>
                    <option value="SC"{{ old('uf_entrega') == 'SC' ? ' selected' : '' }}>SC</option>
                    <option value="SP"{{ old('uf_entrega') == 'SP' ? ' selected' : '' }}>SP</option>
                    <option value="SE"{{ old('uf_entrega') == 'SE' ? ' selected' : '' }}>SE</option>
                    <option value="TO"{{ old('uf_entrega') == 'TO' ? ' selected' : '' }}>TO</option>
                  </select>
                  @error('uf_entrega')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab-obs" role="tabpanel">
              <div class="row">
                <div class="form-group col-md-12">
                  <label class="form-control-label" for="prospec_id">Como nos conheceu?</label>
                  <select class="form-control @error('prospec_id') is-invalid @enderror" id="prospec_id" name="prospec_id">
                    <option value=""></option>
                    @foreach ($meiosProspec as $meio)
                      <option value="{{ $meio->id }}"{{ old('prospec_id') == $meio->id ? ' selected' : '' }}>{{ $meio->descricao }}</option>
                    @endforeach
                  </select>
                  @error('prospec_id')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-12">
                  <label class="form-control-label" for="obs">Observações</label>
                  <textarea class="form-control @error('obs') is-invalid @enderror" id="obs" name="obs" rows="15" placeholder="Observações, comentários, notas...">{{ old('obs') }}</textarea>
                  @error('obs')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('clientes.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </div>
    </form>
  </div>

@endsection
