(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/painel_acompanhamento/painel', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.painel_acompanhamentoPainel = mod.exports;
  }
})(this, function (_jquery) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  //Declaração da nova função (icontains) para usar ao invés do contains
  //Permite busca com case insensitive
  jQuery.expr[':'].icontains = function (a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
  };

  //Document Ready
  (0, _jquery2.default)(document).ready(function () {
    (0, _jquery2.default)('#idcliente').select2({
      dropdownParent: (0, _jquery2.default)('#retrabalho-modal')
    });
  });

  //Arquivar recebimento
  function arquivarRecebimento(id) {
    _jquery2.default.ajax({
      url: arquivarCardUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'id': id
      },
      success: function success(data) {
        refreshColumn('R');
      },
      error: function error(jqXHR, textStatus, _error) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Encerrar Separação
  function encerrarSeparacao(id) {
    _jquery2.default.ajax({
      url: encerrarSeparacaoUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'id': id
      },
      success: function success(data) {
        refreshColumn('S');
      },
      error: function error(jqXHR, textStatus, _error2) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Encerrar Retrabalho
  function encerrarRetrabalho(id) {
    _jquery2.default.ajax({
      url: encerrarRetrabalhoUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'id': id
      },
      success: function success(data) {
        refreshColumn('T');
      },
      error: function error(jqXHR, textStatus, _error3) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Editar Retrabalho
  function editarRetrabalho(id) {
    _jquery2.default.ajax({
      url: separacaoFromCatalogacaoUrl + id,
      type: 'GET',
      success: function success(data) {
        //limpa e atribui a separação
        limpaCamposRetrabalho();
        (0, _jquery2.default)('#idseparacao').val(data.id);
        //localiza retrabalho
        _jquery2.default.ajax({
          url: dadosRetrabalhoUrl + data.retrabalho_id,
          type: 'GET',
          success: function success(data) {
            //dados do retrabalho
            (0, _jquery2.default)('#idretrabalho').val(data.id);
            (0, _jquery2.default)('#observacoes').val(data.observacoes);
            (0, _jquery2.default)('#idcliente').val(data.cliente_id);
            (0, _jquery2.default)('#idcliente').trigger('change');
            //itens
            if (data.itens) {
              data.itens.map(function (item, index) {
                var row = (0, _jquery2.default)('.item-retrabalho').last();
                row.find('select[name*=idtiposervico]').val(item.idtiposervico);
                row.find('select[name*=idmaterial]').val(item.idmaterial);
                row.find('input[name*=milesimos]').val(item.milesimos);
                row.find('input[name*=peso]').val(item.peso);
                row.find('input[name*=item_id]').val(item.id);
                if (item.idmaterial) {
                  var cbCores = row.find('select[name*=idcor]');
                  _jquery2.default.when(preencheCores(item.idmaterial, cbCores)).done(function () {
                    row.find('select[name*=idcor]').val(item.idcor);
                  });
                }

                //Adiciona linha
                row.find('.btn-add-item-retrabalho').trigger('click');
              });
            }

            //modal
            (0, _jquery2.default)('#retrabalho-modal').modal('show');
          },
          error: function error(jqXHR, textStatus, err) {
            console.log(jqXHR);
            window.toastr.error(jqXHR.responseText);
          }
        });
      },
      error: function error(jqXHR, textStatus, err) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseText);
      }
    });
  }

  //Iniciar Preparação
  function iniciarPreparacao(id) {
    _jquery2.default.ajax({
      url: iniciarPreparacaoUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'id': id
      },
      success: function success(data) {
        refreshColumn('F');
      },
      error: function error(jqXHR, textStatus, _error4) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Iniciar Retrabalho
  function iniciarRetrabalho(id) {
    _jquery2.default.ajax({
      url: iniciarRetrabalhoUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'id': id
      },
      success: function success(data) {
        refreshColumn('T');
      },
      error: function error(jqXHR, textStatus, _error5) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Iniciar Expedição
  function iniciarExpedicao(id) {
    _jquery2.default.ajax({
      url: iniciarExpedicaoUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'id': id
      },
      success: function success(data) {
        refreshColumn('C');
      },
      error: function error(jqXHR, textStatus, _error6) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Atualiza a coluna informada
  function refreshColumn(col) {
    var container = null;
    switch (col) {
      case 'R':
        container = (0, _jquery2.default)('#recebimentos');
        break;
      case 'S':
        container = (0, _jquery2.default)('#separacoes');
        break;
      case 'A':
        container = (0, _jquery2.default)('#catalogacoes');
        break;
      case 'F':
        container = (0, _jquery2.default)('#preparacoes');
        break;
      case 'B':
        container = (0, _jquery2.default)('#banhos');
        break;
      case 'T':
        container = (0, _jquery2.default)('#retrabalhos');
        break;
      case 'G':
        container = (0, _jquery2.default)('#revisoes');
        break;
      case 'C':
        container = (0, _jquery2.default)('#expedicoes');
        break;
      case 'L':
        container = (0, _jquery2.default)('#concluidos');
        break;
      default:
        break;
    }

    _jquery2.default.ajax({
      url: panelColumnUrl,
      type: 'GET',
      data: {
        'col': col
      },
      success: function success(data) {
        container.html(data.view);
        //Sortable
        kban = container.find('*[data-plugin="kanban"]');
        opkb = Plugin.getDefaults("kanban");
        opkb.multiDrag = kban.data('multi-drag');
        Sortable.create(kban[0], opkb);
        //Scrollable
        scrlb = container.find('*[data-plugin="scrollable"]');
        opscr = Plugin.getDefaults("scrollable");
        scrlb.asScrollable(opscr);
        filtrar();
      },
      error: function error(jqXHR, textStatus, _error7) {
        window.toastr.error(jqXHR.responseJSON.message);
      }
    });
  }

  //Filtra resultados de acordo com pesquisa
  function filtrar() {
    var term = (0, _jquery2.default)('#search').val();

    if (term != '') {
      (0, _jquery2.default)('.card').addClass('d-none');
    } else {
      (0, _jquery2.default)('.card').removeClass('d-none');
    }

    (0, _jquery2.default)(".text-body:icontains('" + term + "')").parent().parent().parent().removeClass('d-none');
    calcTotais();
  }

  //Calcula totais das colunas
  function calcTotais() {
    (0, _jquery2.default)('.tasks').each(function (i, coluna) {
      var peso_coluna = 0;
      var qtde_cards = 0;
      (0, _jquery2.default)(coluna).find('.card').not('.d-none').each(function (j, bloco) {
        //Peso
        peso_bloco = parseInt((0, _jquery2.default)(bloco).find('.badge-peso').html().replace(' g', '').replace(/\./g, ''));
        peso_coluna = peso_coluna + peso_bloco;
        //Quantidade
        qtde_cards = qtde_cards + 1;
      });
      //Caso queira exibir formatado com o separador de milhar:
      //peso_coluna = peso_coluna.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      if (peso_coluna < 1000) {
        peso_coluna = peso_coluna + ' g';
      } else {
        peso_coluna = peso_coluna / 1000;
        peso_coluna = peso_coluna.toFixed(2) + ' Kg';
      }
      //Peso da coluna
      (0, _jquery2.default)(coluna).find('.totalizador-peso').html(peso_coluna);
      //Itens da coluna
      (0, _jquery2.default)(coluna).find('.totalizador').html(qtde_cards);
    });
  }

  //Limpa campos modal retrabalho
  function limpaCamposRetrabalho() {
    (0, _jquery2.default)('#idseparacao').val('');
    (0, _jquery2.default)('#idretrabalho').val('');
    (0, _jquery2.default)('#idcliente').val('');
    (0, _jquery2.default)('#idcliente').trigger('change');
    (0, _jquery2.default)('#observacoes').val('');
    var lim = (0, _jquery2.default)('.item-retrabalho').length - 1;
    (0, _jquery2.default)('.item-retrabalho').each(function (i, v) {
      if (i < lim) {
        (0, _jquery2.default)(v).remove();
      } else {
        (0, _jquery2.default)(v).find('input, select').each(function (j, elem) {
          (0, _jquery2.default)(elem).val('');
        });
        (0, _jquery2.default)(v).attr('data-index', '0');
      }
    });
  }

  //Preenche Cores
  function preencheCores(id, cbCores) {
    return _jquery2.default.ajax({
      url: coresUrl + id,
      dataType: "json",
      success: function success(data) {
        cbCores.empty();
        cbCores.append('<option></option>');

        for (var k in data) {
          cbCores.append('<option value="' + data[k].id + '">' + data[k].descricao + '</option>');
        }
      }
    });
  }

  //Pesquisa
  (0, _jquery2.default)(document).on('keyup', '#search', function (key) {
    filtrar();
  });

  //Arquivar
  (0, _jquery2.default)(document).on('click', '.action-arquivar', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    arquivarRecebimento(id);
  });

  //Encerrar Separação
  (0, _jquery2.default)(document).on('click', '.action-encerrar-sep', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    encerrarSeparacao(id);
  });

  //Encerrar Retrabalho
  (0, _jquery2.default)(document).on('click', '.action-encerrar-retrab', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    encerrarRetrabalho(id);
  });

  //Iniciar preparação
  (0, _jquery2.default)(document).on('click', '.action-iniciar-prep', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    iniciarPreparacao(id);
  });

  //Iniciar Retrabalho
  (0, _jquery2.default)(document).on('click', '.action-iniciar-retrab', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    iniciarRetrabalho(id);
  });

  //Iniciar Expedição
  (0, _jquery2.default)(document).on('click', '.action-iniciar-exped', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    iniciarExpedicao(id);
  });

  //Editar Retrabalho
  (0, _jquery2.default)(document).on('click', '.edit-retrabalho', function (event) {
    var id = (0, _jquery2.default)(this).parent().parent().parent().parent().data('id');
    editarRetrabalho(id);
  });

  //Add Item Retrabalho
  (0, _jquery2.default)(document).on('click', '.btn-add-item-retrabalho', function () {
    var rowbase = (0, _jquery2.default)('.item-retrabalho').last();
    var container = rowbase.parent();
    var clone = rowbase.clone();
    var index = parseInt(clone.data('index'));
    var cloneindex = index + 1;

    clone.attr('data-index', cloneindex);
    clone.find('input, select').each(function (i, elem) {
      (0, _jquery2.default)(elem).attr('name', (0, _jquery2.default)(elem).attr('name').replace(index, cloneindex));
      (0, _jquery2.default)(elem).val('');
    });

    rowbase.find('.item-retrabalho-controls').removeClass('d-none').addClass('d-flex');
    rowbase.find('.btn-add-item-retrabalho').addClass('d-none');

    container.append(clone);
  });

  //Del Item Retrabalho
  (0, _jquery2.default)(document).on('click', '.btn-remove-item-retrabalho', function (e) {
    e.preventDefault();
    var row = (0, _jquery2.default)(this).parent().parent().parent();
    row.remove();
  });

  (0, _jquery2.default)(document).on('change', 'select[name*="idmaterial"]', function () {
    var id = (0, _jquery2.default)(this).val();
    var cbCores = (0, _jquery2.default)(this).parent().parent().find('select[name*="idcor"]');

    if (id == '') {
      cbCores.empty();
      cbCores.append('<option></option>');
    } else {
      preencheCores(id, cbCores);
    }
  });

  (0, _jquery2.default)(document).on('click', '#btn-registrar', function () {

    var data = (0, _jquery2.default)('#retrabalho-form').serializeArray();

    _jquery2.default.ajax({
      url: storeRetrabalhoUrl,
      type: 'POST',
      data: data,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      success: function success(data) {
        (0, _jquery2.default)('#retrabalho-modal').modal('hide');
        refreshColumn('T');
      }
    });
  });

  //Prevent retrabalho form submit
  (0, _jquery2.default)(document).on('submit', '#retrabalho-form', function (e) {
    e.preventDefault();
  });
});