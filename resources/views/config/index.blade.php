@extends('layouts.app.main')

@push('scripts_plugins')
  <script src="{{ asset('assets/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
@endpush

@push('scripts_page')
  <script>
    (function() {
      $(document).ready(function() {
        $('.mask-hora').mask('00:00');
      });
    })();
  </script>
@endpush

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="page-title font-size-26 font-weight-100">Configurações</h1>
    </div>

    <div class="page-content">
      <form method="post" action="{{ route('config.update') }}" autocomplete="off">
        <input type="hidden" name="_method" value="PUT">
        @csrf
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Dias Úteis</h3>
          </div>
          <div class="panel-body container-fluid">
            <div class="row">
              <div class="col-2"></div>
              <div class="col-2">De</div>
              <div class="col-2">Até</div>
              <div class="col-1"></div>
              <div class="col-2">De</div>
              <div class="col-2">Até</div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="segunda" {{ old('segunda', env('DIA_UTIL_SEGUNDA')) ? ' checked' : '' }} />
                  <label for="segunda">Segunda-feira</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('seg_hora1') is-invalid @enderror" id="seg_hora1" name="seg_hora1" value="{{ old('seg_hora1', env('DIA_UTIL_SEG_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('seg_hora2') is-invalid @enderror" id="seg_hora2" name="seg_hora2" value="{{ old('seg_hora2', env('DIA_UTIL_SEG_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('seg_hora3') is-invalid @enderror" id="seg_hora3" name="seg_hora3" value="{{ old('seg_hora3', env('DIA_UTIL_SEG_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('seg_hora4') is-invalid @enderror" id="seg_hora4" name="seg_hora4" value="{{ old('seg_hora4', env('DIA_UTIL_SEG_HORA4')) }}" />
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="terca" {{ old('terca', env('DIA_UTIL_TERCA')) ? ' checked' : '' }} />
                  <label for="terca">Terça-feira</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('ter_hora1') is-invalid @enderror" id="ter_hora1" name="ter_hora1" value="{{ old('ter_hora1', env('DIA_UTIL_TER_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('ter_hora2') is-invalid @enderror" id="ter_hora2" name="ter_hora2" value="{{ old('ter_hora2', env('DIA_UTIL_TER_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('ter_hora3') is-invalid @enderror" id="ter_hora3" name="ter_hora3" value="{{ old('ter_hora3', env('DIA_UTIL_TER_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('ter_hora4') is-invalid @enderror" id="ter_hora4" name="ter_hora4" value="{{ old('ter_hora4', env('DIA_UTIL_TER_HORA4')) }}" />
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="quarta" {{ old('quarta', env('DIA_UTIL_QUARTA')) ? ' checked' : '' }} />
                  <label for="quarta">Quarta-feira</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qua_hora1') is-invalid @enderror" id="qua_hora1" name="qua_hora1" value="{{ old('qua_hora1', env('DIA_UTIL_QUA_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qua_hora2') is-invalid @enderror" id="qua_hora2" name="qua_hora2" value="{{ old('qua_hora2', env('DIA_UTIL_QUA_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qua_hora3') is-invalid @enderror" id="qua_hora3" name="qua_hora3" value="{{ old('qua_hora3', env('DIA_UTIL_QUA_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qua_hora4') is-invalid @enderror" id="qua_hora4" name="qua_hora4" value="{{ old('qua_hora4', env('DIA_UTIL_QUA_HORA4')) }}" />
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="quinta" {{ old('quinta', env('DIA_UTIL_QUINTA')) ? ' checked' : '' }} />
                  <label for="quinta">Quinta-feira</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qui_hora1') is-invalid @enderror" id="qui_hora1" name="qui_hora1" value="{{ old('qui_hora1', env('DIA_UTIL_QUI_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qui_hora2') is-invalid @enderror" id="qui_hora2" name="qui_hora2" value="{{ old('qui_hora2', env('DIA_UTIL_QUI_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qui_hora3') is-invalid @enderror" id="qui_hora3" name="qui_hora3" value="{{ old('qui_hora3', env('DIA_UTIL_QUI_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('qui_hora4') is-invalid @enderror" id="qui_hora4" name="qui_hora4" value="{{ old('qui_hora4', env('DIA_UTIL_QUI_HORA4')) }}" />
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="sexta" {{ old('sexta', env('DIA_UTIL_SEXTA')) ? ' checked' : '' }} />
                  <label for="sexta">Sexta-feira</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sex_hora1') is-invalid @enderror" id="sex_hora1" name="sex_hora1" value="{{ old('sex_hora1', env('DIA_UTIL_SEX_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sex_hora2') is-invalid @enderror" id="sex_hora2" name="sex_hora2" value="{{ old('sex_hora2', env('DIA_UTIL_SEX_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sex_hora3') is-invalid @enderror" id="sex_hora3" name="sex_hora3" value="{{ old('sex_hora3', env('DIA_UTIL_SEX_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sex_hora4') is-invalid @enderror" id="sex_hora4" name="sex_hora4" value="{{ old('sex_hora4', env('DIA_UTIL_SEX_HORA4')) }}" />
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="sabado" {{ old('sabado', env('DIA_UTIL_SABADO')) ? ' checked' : '' }} />
                  <label for="sabado">Sábado</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sab_hora1') is-invalid @enderror" id="sab_hora1" name="sab_hora1" value="{{ old('sab_hora1', env('DIA_UTIL_SAB_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sab_hora2') is-invalid @enderror" id="sab_hora2" name="sab_hora2" value="{{ old('sab_hora2', env('DIA_UTIL_SAB_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sab_hora3') is-invalid @enderror" id="sab_hora3" name="sab_hora3" value="{{ old('sab_hora3', env('DIA_UTIL_SAB_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('sab_hora4') is-invalid @enderror" id="sab_hora4" name="sab_hora4" value="{{ old('sab_hora4', env('DIA_UTIL_SAB_HORA4')) }}" />
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="checkbox-custom checkbox-success">
                  <input type="checkbox" id="domingo" {{ old('domingo', env('DIA_UTIL_DOMINGO')) ? ' checked' : '' }} />
                  <label for="domingo">Domingo</label>
                </div>
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('dom_hora1') is-invalid @enderror" id="dom_hora1" name="dom_hora1" value="{{ old('dom_hora1', env('DIA_UTIL_DOM_HORA1')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('dom_hora2') is-invalid @enderror" id="dom_hora2" name="dom_hora2" value="{{ old('dom_hora2', env('DIA_UTIL_DOM_HORA2')) }}" />
              </div>
              <div class="col-1"></div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('dom_hora3') is-invalid @enderror" id="dom_hora3" name="dom_hora3" value="{{ old('dom_hora3', env('DIA_UTIL_DOM_HORA3')) }}" />
              </div>
              <div class="col-2">
                <input type="text" class="form-control mask-hora @error('dom_hora4') is-invalid @enderror" id="dom_hora4" name="dom_hora4" value="{{ old('dom_hora4', env('DIA_UTIL_DOM_HORA4')) }}" />
              </div>
            </div>
          </div>
        </div>

        {{-- 
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Feriados</h3>
          </div>
          <div class="panel-body container-fluid">
            <div class="row">
              <div class="col-12">
                
              </div>
            </div>
          </div>
        </div>
        --}}
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 text-right">
              <div class="form-group">
                <a class="btn btn-default" href="{{ route('home') }}" role="button">Cancelar</a>
                <button type="submit" class="btn btn-success">Salvar</button>
              </div>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

@endsection
