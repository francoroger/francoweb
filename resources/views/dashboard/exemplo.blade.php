@extends('layouts.app.main')

@push('stylesheets_plugins')
  <link rel="stylesheet" href="{{ asset('assets/vendor/slick-carousel/slick.css') }}">
@endpush

@push('scripts_plugins')
  <script src="{{ asset('assets/vendor/slick-carousel/slick.js') }}"></script>
@endpush

@push('scripts_page')
  <script src="{{ asset('assets/examples/js/uikit/carousel.js') }}"></script>
@endpush

@section('content')
  <div class="page">
    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-lg-3">
          <div class="card p-30 flex-row justify-content-between">
            <div class="white">
              <i class="icon icon-circle icon-2x wb-tag bg-red-600" aria-hidden="true"></i>
            </div>
            <div class="counter counter-md counter text-right">
              <div class="counter-number-group">
                <span class="counter-number">13</span>
              </div>
              <div class="counter-label text-capitalize font-size-16">Em Catalogação</div>
            </div>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="card p-30 flex-row justify-content-between">
            <div class="white">
              <i class="icon icon-circle icon-2x wb-wrench bg-purple-600" aria-hidden="true"></i>
            </div>
            <div class="counter counter-md counter text-right">
              <div class="counter-number-group">
                <span class="counter-number">25</span>
              </div>
              <div class="counter-label text-capitalize font-size-16">Em Produção</div>
            </div>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="card p-30 flex-row justify-content-between">
            <div class="white">
              <i class="icon icon-circle icon-2x wb-time bg-yellow-600" aria-hidden="true"></i>
            </div>
            <div class="counter counter-md counter text-right">
              <div class="counter-number-group">
                <span class="counter-number">9</span>
              </div>
              <div class="counter-label text-capitalize font-size-16">Em Revisão</div>
            </div>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="card p-30 flex-row justify-content-between">
            <div class="white">
              <i class="icon icon-circle icon-2x fa-flag-checkered bg-green-600" aria-hidden="true"></i>
            </div>
            <div class="counter counter-md counter text-right">
              <div class="counter-number-group">
                <span class="counter-number">11</span>
              </div>
              <div class="counter-label text-capitalize font-size-16">Concluídos</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title text-center">Programação Semanal</h3>
            </div>
            <div class="panel-body">
              <div class="slider" id="exampleMultipleItems">
                <div>
                  <h3>9480 g</h3>
                  <h6>
                    09/09/2019 <br>
                    <small>SEGUNDA-FEIRA</small>
                  </h6>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-left">Cliente</th>
                        <th class="text-right">Peso</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">ADRIANA LUCILENE</td>
                        <td class="text-right">1130</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">ZARES</td>
                        <td class="text-right">5520</td>
                        <td class="text-center"><span class="badge badge-warning">Em Revisão</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">VALTER MS</td>
                        <td class="text-right">3150</td>
                        <td class="text-center"><span class="badge badge-info">Separação</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">SUISSA JOIAS</td>
                        <td class="text-right">980</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div>
                  <h3>6660 g</h3>
                  <h6>
                    10/09/2019 <br>
                    <small>TERÇA-FEIRA</small>
                  </h6>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-left">Cliente</th>
                        <th class="text-right">Peso</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">ELIANE VAGO</td>
                        <td class="text-right">1400</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">AMBAYA</td>
                        <td class="text-right">552</td>
                        <td class="text-center"><span class="badge badge-warning">Em Revisão</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">FABIO VITORIA</td>
                        <td class="text-right">874</td>
                        <td class="text-center"><span class="badge badge-info">Separação</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">HENRIQUE CABRAL</td>
                        <td class="text-right">1690</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">CAPRI BIJUTERIAS</td>
                        <td class="text-right">470</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">MARCELO MOGI</td>
                        <td class="text-right">996</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div>
                  <h3>2000 g</h3>
                  <h6>
                    11/09/2019 <br>
                    <small>QUARTA-FEIRA</small>
                  </h6>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-left">Cliente</th>
                        <th class="text-right">Peso</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">GAZOLI</td>
                        <td class="text-right">785</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">GIANNE AZEVEDO</td>
                        <td class="text-right">1685</td>
                        <td class="text-center"><span class="badge badge-info">Separação</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">DEVANIR</td>
                        <td class="text-right">444</td>
                        <td class="text-center"><span class="badge badge-warning">Em Revisão</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">CARLÃO</td>
                        <td class="text-right">980</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">KIMERA</td>
                        <td class="text-right">940</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div>
                  <h3>689 g</h3>
                  <h6>
                    12/09/2019 <br>
                    <small>QUINTA-FEIRA</small>
                  </h6>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-left">Cliente</th>
                        <th class="text-right">Peso</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">LUIS EDUARDO</td>
                        <td class="text-right">330</td>
                        <td class="text-center"><span class="badge badge-info">Separação</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">SELMA</td>
                        <td class="text-right">349</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div>
                  <h3>5940 g</h3>
                  <h6>
                    13/09/2019 <br>
                    <small>SEXTA-FEIRA</small>
                  </h6>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-left">Cliente</th>
                        <th class="text-right">Peso</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left">PEDRO GOMES</td>
                        <td class="text-right">1130</td>
                        <td class="text-center"><span class="badge badge-info">Separação</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">ALESSANDRA SC</td>
                        <td class="text-right">5520</td>
                        <td class="text-center"><span class="badge badge-warning">Em Revisão</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">PEDRO GOMES</td>
                        <td class="text-right">3150</td>
                        <td class="text-center"><span class="badge badge-info">Separação</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">MIGUEL</td>
                        <td class="text-right">980</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">JENIFER</td>
                        <td class="text-right">980</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">CLAUDIA RORAIMA</td>
                        <td class="text-right">980</td>
                        <td class="text-center"><span class="badge badge-success">Concluído</span></td>
                      </tr>
                      <tr>
                        <td class="text-left">CLAUDIA BOTTOZO</td>
                        <td class="text-right">980</td>
                        <td class="text-center"><span class="badge badge-warning">Em Revisão</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
