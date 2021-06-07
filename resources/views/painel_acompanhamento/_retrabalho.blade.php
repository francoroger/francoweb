<!-- Modal -->
<div class="modal fade" id="retrabalho-modal" tabindex="-1" role="dialog" aria-labelledby="modalRetrabalhoLabel"
  aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRetrabalhoLabel">Retrabalho</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="retrabalho-form">
          <input type="hidden" name="idseparacao" id="idseparacao">
          <input type="hidden" name="idretrabalho" id="idretrabalho">
          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label font-weight-400" for="idcliente">Cliente</label>
              <select class="form-control" id="idcliente" name="idcliente" style="width:100%;">
                <option value=""></option>
                @foreach ($clientes as $cliente)
                  <option value="{{ $cliente->id }}" {{ $cliente->ativo ? '' : ' disabled' }}>
                    {{ $cliente->identificacao }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label class="form-control-label font-weight-400" for="observacoes">Observações</label>
              <textarea class="form-control" name="observacoes" id="observacoes" rows="3"></textarea>
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
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btn-registrar"><i class="fa fa-tick"></i> Salvar</button>
      </div>
    </div>
  </div>
</div>
