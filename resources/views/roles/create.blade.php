@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/select2/select2.full.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/js/Plugin/select2.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Cadastrar Perfil de Acesso</h1>
    </div>

    <div class="page-content">
      <form class="panel" method="post" action="{{ route('roles.store') }}" autocomplete="off">
        @csrf
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label" for="name">Nome <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nome" value="{{ old('name') }}" required />
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>

          <div class="table-responsive mt-10">
            <table class="table table-borderless table-striped table-vcenter">
              <thead>
                <tr>
                  <th colspan="5" class="text-center">Permissões de Acesso</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($permissions as $group => $groups)
                  @if ($group)
                    <tr class="table-active">
                      <td class="font-size-h6 text-uppercase font-w700">{{ $group }}</td>
                      <td class="text-center" style="width: 100px;">ACESSAR</td>
                      <td class="text-center" style="width: 100px;">ADICIONAR</td>
                      <td class="text-center" style="width: 100px;">ALTERAR</td>
                      <td class="text-center" style="width: 100px;">EXCLUIR</td>
                    </tr>
                  @endif
                  @foreach ($groups as $name => $names)
                    @if ($names->whereIn('method', ['index', 'create', 'edit', 'destroy'])->count() > 0)
                      <tr>
                        <td class="font-w700">
                          {{ $name }}
                        </td>
                        <td class="text-center">
                          @if ($names->where('method', 'index')->count() > 0)
                          <input type="checkbox" data-plugin="switchery" data-color="#526069"
                          data-group="{{ $loop->parent->index . '_index' }}"
                           name="permissions[]"
                            value="{{ $names->where('method', 'index')->first()->name }}"
                             checked>
                          @endif
                        </td>
                        <td class="text-center">
                          @if ($names->where('method', 'create')->count() > 0)
                          <input type="checkbox" data-plugin="switchery" data-color="#526069"
                          data-group="{{ $loop->parent->index . '_create' }}"
                           name="permissions[]"
                            value="{{ $names->where('method', 'create')->first()->name }}"
                             checked>
                          @endif
                        </td>
                        <td class="text-center">
                          @if ($names->where('method', 'edit')->count() > 0)
                          <input type="checkbox" data-plugin="switchery" data-color="#526069"
                          data-group="{{ $loop->parent->index . '_edit' }}"
                           name="permissions[]"
                            value="{{ $names->where('method', 'edit')->first()->name }}"
                             checked>
                          @endif
                        </td>
                        <td class="text-center">
                          @if ($names->where('method', 'destroy')->count() > 0)
                          <input type="checkbox" data-plugin="switchery" data-color="#526069"
                          data-group="{{ $loop->parent->index . '_destroy' }}"
                           name="permissions[]"
                            value="{{ $names->where('method', 'destroy')->first()->name }}"
                             checked>
                          @endif
                        </td>
                      </tr>
                    @endif
                    @if ($names->whereNotIn('method', ['index', 'create', 'edit', 'destroy'])->count() > 0)
                      @foreach ($names as $specialPermission)
                        <tr>
                          <td class="font-w700" colspan="5">
                            <input type="checkbox" data-plugin="switchery" data-color="#526069"
                               data-group="{{ $loop->parent->parent->index . '_special' }}"
                                name="permissions[]"
                                 value="{{ $specialPermission->name }}" 
                                 checked>
                                 {{ $specialPermission->feature }}
                          </td>
                        </tr>
                      @endforeach
                    @endif
                  @endforeach
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
        <div class="panel-footer text-right">
          <div class="form-group">
            <a class="btn btn-default" href="{{ route('roles.index') }}" role="button">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>


    </div>
  </div>

@endsection
