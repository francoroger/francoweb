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
  <script src="{{ asset('assets/modules/js/fornecedores/edit.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <form class="panel" method="post" action="{{ route('fornecedores.update', $fornecedor->id) }}" autocomplete="off">
      <input type="hidden" name="_method" value="PUT">
      @csrf
      <div class="page-header">
        <h1 class="page-title font-size-26 font-weight-100">{{ $fornecedor->nome }}</h1>
        <div class="page-header-actions">
          <div class="float-left mr-10">
            <input type="hidden" name="ativo" value="0">
            <input type="checkbox" id="ativo" name="ativo" data-plugin="switchery" value="1" {{ old('ativo', $fornecedor->ativo) == 1 ? 'checked' : '' }} />
          </div>
          <label class="pt-3" for="ativo">Ativo</label>
        </div>
      </div>
      <div class="page-content">
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="form-group col-md-9">
              <label class="form-control-label" for="nome">Apelido <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" placeholder="Nome Fantasia / Apelido" value="{{ old('nome', $fornecedor->nome) }}" required />
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
                  <input type="radio" id="pessoa_fisica" name="tipopessoa" value="F" {{ old('tipopessoa', $fornecedor->tipopessoa) == 'F' ? 'checked' : '' }} />
                  <label for="pessoa_fisica">Física</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                  <input type="radio" id="pessoa_juridica" name="tipopessoa" value="J" {{ old('tipopessoa', $fornecedor->tipopessoa) == 'J' ? 'checked' : '' }} />
                  <label for="pessoa_juridica">Jurídica</label>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <label class="form-control-label" for="cpf">CPF / CNPJ</label>
              <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" placeholder="CPF / CNPJ" value="{{ old('cpf', $fornecedor->cpf) }}" />
              @error('cpf')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-9">
              <label class="form-control-label" for="rzsc">Razão Social / Nome</label>
              <input type="text" class="form-control @error('rzsc') is-invalid @enderror" id="rzsc" name="rzsc" placeholder="Razão Social / Nome Completo" value="{{ old('rzsc', $fornecedor->rzsc) }}" />
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
              <input type="text" class="form-control @error('inscest') is-invalid @enderror" id="inscest" name="inscest" placeholder="Inscrição Estadual / RG" value="{{ old('inscest', $fornecedor->inscest) }}" />
              @error('inscest')
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
              <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" placeholder="Telefone Principal" value="{{ old('telefone', $fornecedor->telefone) }}" data-plugin="mask" data-type="cellphone" />
              @error('telefone')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-3">
              <label class="form-control-label" for="celular">Celular <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" placeholder="Celular Principal" value="{{ old('celular', $fornecedor->celular) }}" data-plugin="mask" data-type="cellphone" required />
              @error('celular')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label class="form-control-label" for="email">E-mail</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail Principal" value="{{ old('email', $fornecedor->email) }}" />
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-3">
              <input type="text" class="form-control @error('telefone2') is-invalid @enderror" id="telefone2" name="telefone2" placeholder="Telefone Secundário" value="{{ old('telefone2', $fornecedor->telefone2) }}" data-plugin="mask" data-type="cellphone" />
              @error('telefone2')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-3">
              <input type="text" class="form-control @error('celular2') is-invalid @enderror" id="celular2" name="celular2" placeholder="Celular Secundário" value="{{ old('celular2', $fornecedor->celular2) }}" data-plugin="mask" data-type="cellphone" />
              @error('celular2')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <input type="email" class="form-control @error('email2') is-invalid @enderror" id="email2" name="email2" placeholder="E-mail Secundário" value="{{ old('email2', $fornecedor->email2) }}" />
              @error('email2')
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
              <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" placeholder="CEP" value="{{ old('cep', $fornecedor->cep) }}" data-plugin="mask" data-pattern="00000-000" />
              @error('cep')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-9">
              <label class="form-control-label" for="endereco">Endereço</label>
              <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" placeholder="Endereço" value="{{ old('endereco', $fornecedor->endereco) }}" />
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
              <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" placeholder="Número" value="{{ old('numero', $fornecedor->numero) }}" />
              @error('numero')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-4">
              <label class="form-control-label" for="compl">Complemento</label>
              <input type="text" class="form-control @error('compl') is-invalid @enderror" id="compl" name="compl" placeholder="Complemento" value="{{ old('compl', $fornecedor->compl) }}" />
              @error('compl')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-md-5">
              <label class="form-control-label" for="bairro">Bairro</label>
              <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" placeholder="Bairro" value="{{ old('bairro', $fornecedor->bairro) }}" />
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
              <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" placeholder="Cidade" value="{{ old('cidade', $fornecedor->cidade) }}" />
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
                <option value="AC"{{ old('uf', $fornecedor->uf) == 'AC' ? ' selected' : '' }}>AC</option>
                <option value="AL"{{ old('uf', $fornecedor->uf) == 'AL' ? ' selected' : '' }}>AL</option>
                <option value="AP"{{ old('uf', $fornecedor->uf) == 'AP' ? ' selected' : '' }}>AP</option>
                <option value="AM"{{ old('uf', $fornecedor->uf) == 'AM' ? ' selected' : '' }}>AM</option>
                <option value="BA"{{ old('uf', $fornecedor->uf) == 'BA' ? ' selected' : '' }}>BA</option>
                <option value="CE"{{ old('uf', $fornecedor->uf) == 'CE' ? ' selected' : '' }}>CE</option>
                <option value="DF"{{ old('uf', $fornecedor->uf) == 'DF' ? ' selected' : '' }}>DF</option>
                <option value="ES"{{ old('uf', $fornecedor->uf) == 'ES' ? ' selected' : '' }}>ES</option>
                <option value="GO"{{ old('uf', $fornecedor->uf) == 'GO' ? ' selected' : '' }}>GO</option>
                <option value="MA"{{ old('uf', $fornecedor->uf) == 'MA' ? ' selected' : '' }}>MA</option>
                <option value="MT"{{ old('uf', $fornecedor->uf) == 'MT' ? ' selected' : '' }}>MT</option>
                <option value="MS"{{ old('uf', $fornecedor->uf) == 'MS' ? ' selected' : '' }}>MS</option>
                <option value="MG"{{ old('uf', $fornecedor->uf) == 'MG' ? ' selected' : '' }}>MG</option>
                <option value="PA"{{ old('uf', $fornecedor->uf) == 'PA' ? ' selected' : '' }}>PA</option>
                <option value="PB"{{ old('uf', $fornecedor->uf) == 'PB' ? ' selected' : '' }}>PB</option>
                <option value="PR"{{ old('uf', $fornecedor->uf) == 'PR' ? ' selected' : '' }}>PR</option>
                <option value="PE"{{ old('uf', $fornecedor->uf) == 'PE' ? ' selected' : '' }}>PE</option>
                <option value="PI"{{ old('uf', $fornecedor->uf) == 'PI' ? ' selected' : '' }}>PI</option>
                <option value="RJ"{{ old('uf', $fornecedor->uf) == 'RJ' ? ' selected' : '' }}>RJ</option>
                <option value="RN"{{ old('uf', $fornecedor->uf) == 'RN' ? ' selected' : '' }}>RN</option>
                <option value="RS"{{ old('uf', $fornecedor->uf) == 'RS' ? ' selected' : '' }}>RS</option>
                <option value="RO"{{ old('uf', $fornecedor->uf) == 'RO' ? ' selected' : '' }}>RO</option>
                <option value="RR"{{ old('uf', $fornecedor->uf) == 'RR' ? ' selected' : '' }}>RR</option>
                <option value="SC"{{ old('uf', $fornecedor->uf) == 'SC' ? ' selected' : '' }}>SC</option>
                <option value="SP"{{ old('uf', $fornecedor->uf) == 'SP' ? ' selected' : '' }}>SP</option>
                <option value="SE"{{ old('uf', $fornecedor->uf) == 'SE' ? ' selected' : '' }}>SE</option>
                <option value="TO"{{ old('uf', $fornecedor->uf) == 'TO' ? ' selected' : '' }}>TO</option>
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
              <textarea class="form-control @error('obs') is-invalid @enderror" id="obs" name="obs" rows="15" placeholder="Observações, comentários, notas...">{{ old('obs', $fornecedor->obs) }}</textarea>
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
            <a class="btn btn-default" href="{{ route('fornecedores.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </div>
    </form>
  </div>

@endsection
