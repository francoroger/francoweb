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
  <script src="{{ asset('assets/modules/js/guias/edit.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <form class="panel" method="post" action="{{ route('guias.update', $guia->id) }}" autocomplete="off">
      <input type="hidden" name="_method" value="PUT">
      @csrf
      <div class="page-header">
        <h1 class="page-title font-size-26 font-weight-100">{{ $guia->nome }}</h1>
        <div class="page-header-actions">
          <div class="float-left mr-10">
            <input type="hidden" name="ativo" value="0">
            <input type="checkbox" id="ativo" name="ativo" data-plugin="switchery" value="1" {{ old('ativo', $guia->ativo) == 1 ? 'checked' : '' }} />
          </div>
          <label class="pt-3" for="ativo">Ativo</label>
        </div>
      </div>
      <div class="page-content">
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="form-group col-md-9">
              <label class="form-control-label" for="nome">Apelido <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" placeholder="Nome Fantasia / Apelido" value="{{ old('nome', $guia->nome) }}" required />
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
                  <input type="radio" id="pessoa_fisica" name="tipopessoa" value="F" {{ old('tipopessoa', $guia->tipopessoa) == 'F' ? 'checked' : '' }} />
                  <label for="pessoa_fisica">Física</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                  <input type="radio" id="pessoa_juridica" name="tipopessoa" value="J" {{ old('tipopessoa', $guia->tipopessoa) == 'J' ? 'checked' : '' }} />
                  <label for="pessoa_juridica">Jurídica</label>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="cpf">CPF / CNPJ</label>
              <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" placeholder="CPF / CNPJ" value="{{ old('cpf', $guia->cpf) }}" />
              @error('cpf')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-9">
              <label class="form-control-label" for="rzsc">Razão Social / Nome</label>
              <input type="text" class="form-control @error('rzsc') is-invalid @enderror" id="rzsc" name="rzsc" placeholder="Razão Social / Nome Completo" value="{{ old('rzsc', $guia->rzsc) }}" />
              @error('rzsc')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="tipocomissao">Tipo de Comissão <span class="text-danger">*</span></label>
              <select class="form-control @error('tipocomissao') is-invalid @enderror" id="tipocomissao" name="tipocomissao" required>
                <option value=""></option>
                <option value="F"{{ old('tipocomissao', $guia->tipocomissao) == 'F' ? ' selected' : '' }}>Fixa</option>
                <option value="V"{{ old('tipocomissao', $guia->tipocomissao) == 'V' ? ' selected' : '' }}>Variável</option>
              </select>
              @error('tipocomissao')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <h4 class="example-title">Contato</h4>

          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="telefone">Telefone</label>
              <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" placeholder="Telefone Principal" value="{{ old('telefone', $guia->telefone) }}" data-plugin="mask" data-type="cellphone" />
              @error('telefone')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-3">
              <label class="form-control-label" for="celular">Celular <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" placeholder="Celular Principal" value="{{ old('celular', $guia->celular) }}" data-plugin="mask" data-type="cellphone" required />
              @error('celular')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label class="form-control-label" for="email">E-mail</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail Principal" value="{{ old('email', $guia->email) }}" />
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <input type="text" class="form-control @error('telefone2') is-invalid @enderror" id="telefone2" name="telefone2" placeholder="Telefone Secundário" value="{{ old('telefone2', $guia->telefone2) }}" data-plugin="mask" data-type="cellphone" />
              @error('telefone2')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-3">
              <input type="text" class="form-control @error('celular2') is-invalid @enderror" id="celular2" name="celular2" placeholder="Celular Secundário" value="{{ old('celular2', $guia->celular2) }}" data-plugin="mask" data-type="cellphone" />
              @error('celular2')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <input type="text" class="form-control @error('telefone3') is-invalid @enderror" id="telefone3" name="telefone3" placeholder="Telefone Alternativo" value="{{ old('telefone3', $guia->telefone3) }}" data-plugin="mask" data-type="cellphone" />
              @error('telefone3')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-3">
              <input type="text" class="form-control @error('celular3') is-invalid @enderror" id="celular3" name="celular3" placeholder="Celular Alternativo" value="{{ old('celular3', $guia->celular3) }}" data-plugin="mask" data-type="cellphone" />
              @error('celular2')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <h4 class="example-title">Endereço</h4>

          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="cep">CEP</label>
              <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" placeholder="CEP" value="{{ old('cep', $guia->cep) }}" data-plugin="mask" data-pattern="00000-000" />
              @error('cep')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-9">
              <label class="form-control-label" for="endereco">Endereço</label>
              <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" placeholder="Endereço" value="{{ old('endereco', $guia->endereco) }}" />
              @error('endereco')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="numero">Número</label>
              <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" placeholder="Número" value="{{ old('numero', $guia->numero) }}" />
              @error('numero')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-4">
              <label class="form-control-label" for="compl">Complemento</label>
              <input type="text" class="form-control @error('compl') is-invalid @enderror" id="compl" name="compl" placeholder="Complemento" value="{{ old('compl', $guia->compl) }}" />
              @error('compl')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-5">
              <label class="form-control-label" for="bairro">Bairro</label>
              <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" placeholder="Bairro" value="{{ old('bairro', $guia->bairro) }}" />
              @error('bairro')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-10">
              <label class="form-control-label" for="cidade">Cidade</label>
              <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" placeholder="Cidade" value="{{ old('cidade', $guia->cidade) }}" />
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
                <option value="AC"{{ old('uf', $guia->uf) == 'AC' ? ' selected' : '' }}>AC</option>
                <option value="AL"{{ old('uf', $guia->uf) == 'AL' ? ' selected' : '' }}>AL</option>
                <option value="AP"{{ old('uf', $guia->uf) == 'AP' ? ' selected' : '' }}>AP</option>
                <option value="AM"{{ old('uf', $guia->uf) == 'AM' ? ' selected' : '' }}>AM</option>
                <option value="BA"{{ old('uf', $guia->uf) == 'BA' ? ' selected' : '' }}>BA</option>
                <option value="CE"{{ old('uf', $guia->uf) == 'CE' ? ' selected' : '' }}>CE</option>
                <option value="DF"{{ old('uf', $guia->uf) == 'DF' ? ' selected' : '' }}>DF</option>
                <option value="ES"{{ old('uf', $guia->uf) == 'ES' ? ' selected' : '' }}>ES</option>
                <option value="GO"{{ old('uf', $guia->uf) == 'GO' ? ' selected' : '' }}>GO</option>
                <option value="MA"{{ old('uf', $guia->uf) == 'MA' ? ' selected' : '' }}>MA</option>
                <option value="MT"{{ old('uf', $guia->uf) == 'MT' ? ' selected' : '' }}>MT</option>
                <option value="MS"{{ old('uf', $guia->uf) == 'MS' ? ' selected' : '' }}>MS</option>
                <option value="MG"{{ old('uf', $guia->uf) == 'MG' ? ' selected' : '' }}>MG</option>
                <option value="PA"{{ old('uf', $guia->uf) == 'PA' ? ' selected' : '' }}>PA</option>
                <option value="PB"{{ old('uf', $guia->uf) == 'PB' ? ' selected' : '' }}>PB</option>
                <option value="PR"{{ old('uf', $guia->uf) == 'PR' ? ' selected' : '' }}>PR</option>
                <option value="PE"{{ old('uf', $guia->uf) == 'PE' ? ' selected' : '' }}>PE</option>
                <option value="PI"{{ old('uf', $guia->uf) == 'PI' ? ' selected' : '' }}>PI</option>
                <option value="RJ"{{ old('uf', $guia->uf) == 'RJ' ? ' selected' : '' }}>RJ</option>
                <option value="RN"{{ old('uf', $guia->uf) == 'RN' ? ' selected' : '' }}>RN</option>
                <option value="RS"{{ old('uf', $guia->uf) == 'RS' ? ' selected' : '' }}>RS</option>
                <option value="RO"{{ old('uf', $guia->uf) == 'RO' ? ' selected' : '' }}>RO</option>
                <option value="RR"{{ old('uf', $guia->uf) == 'RR' ? ' selected' : '' }}>RR</option>
                <option value="SC"{{ old('uf', $guia->uf) == 'SC' ? ' selected' : '' }}>SC</option>
                <option value="SP"{{ old('uf', $guia->uf) == 'SP' ? ' selected' : '' }}>SP</option>
                <option value="SE"{{ old('uf', $guia->uf) == 'SE' ? ' selected' : '' }}>SE</option>
                <option value="TO"{{ old('uf', $guia->uf) == 'TO' ? ' selected' : '' }}>TO</option>
              </select>
              @error('uf')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <h4 class="example-title">Informações Adicionais</h4>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="obs">Observações</label>
              <textarea class="form-control @error('obs') is-invalid @enderror" id="obs" name="obs" rows="15" placeholder="Observações, comentários, notas...">{{ old('obs', $guia->obs) }}</textarea>
              @error('obs')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('guias.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </div>
    </form>
  </div>

@endsection
