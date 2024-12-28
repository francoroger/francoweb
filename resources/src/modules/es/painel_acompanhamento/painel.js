import $ from 'jquery';
import * as Site from 'Site';

//Declaração da nova função (icontains) para usar ao invés do contains
//Permite busca com case insensitive
jQuery.expr[':'].icontains = function (a, i, m) {
  return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

//Document Ready
$(document).ready(function () {
  $('#idcliente').select2({
    dropdownParent: $('#retrabalho-modal')
  });
});

//Move cards
function doChangeEvent(evt) {
  let from = $(evt.from);
  let to = $(evt.to);
  let item = $(evt.item);
  let items = $(evt.items);
  let ids = [];

  if (items.length > 0) {
    items.each(function (index, element) {
      ids.push($(element).data('id'));
    });
  } else {
    ids.push(item.data('id'));
  }

  if (to.data('status') === 'T') {
    if (!confirm('Deseja realmente lançar um retrabalho?')) {
      refreshColumn(to.data('status'));
      refreshColumn(from.data('status'));
      return;
    }

    let item_id = item.data('id');
    $.ajax({
      url: separacaoFromCatalogacaoUrl + item_id,
      type: 'GET',
      success: function (data) {
        $('#idseparacao').val(data.id);
        $('#idcliente').val(data.cliente_id);
        $('#idcliente').trigger('change');
        $('#retrabalho-modal').modal('show');
      },
      error: function (jqXHR, textStatus, err) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseText)
        refreshColumn(to.data('status'));
        refreshColumn(from.data('status'));
      }
    });
  }

  if (from !== to) {
    $.ajax({
      url: panelMoveUrl,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      type: 'POST',
      data: {
        'ids': ids,
        'from': from.data('status'),
        'to': to.data('status')
      },
      success: function (data) {
        if (to.data('status') != 'T') {
          refreshColumn(to.data('status'));
        }
        refreshColumn(from.data('status'));
      },
      error: function (jqXHR, textStatus, err) {
        console.log(jqXHR);
        window.toastr.error(jqXHR.responseText)
        refreshColumn(to.data('status'));
        refreshColumn(from.data('status'));
      }
    });
  }
}

//Arquivar recebimento
function arquivarRecebimento(id) {
  $.ajax({
    url: arquivarCardUrl,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    type: 'POST',
    data: {
      'id': id
    },
    success: function (data) {
      refreshColumn('R');
    },
    error: function (jqXHR, textStatus, error) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Encerrar Separação
function encerrarSeparacao(id) {
  $.ajax({
    url: encerrarSeparacaoUrl,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    type: 'POST',
    data: {
      'id': id
    },
    success: function (data) {
      refreshColumn('S');
    },
    error: function (jqXHR, textStatus, error) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Encerrar Retrabalho
function encerrarRetrabalho(id) {
  $.ajax({
    url: encerrarRetrabalhoUrl,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    type: 'POST',
    data: {
      'id': id
    },
    success: function (data) {
      refreshColumn('T');
    },
    error: function (jqXHR, textStatus, error) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Editar Retrabalho
function editarRetrabalho(id) {
  $.ajax({
    url: separacaoFromCatalogacaoUrl + id,
    type: 'GET',
    success: function (data) {
      //limpa e atribui a separação
      limpaCamposRetrabalho();
      $('#idseparacao').val(data.id);
      //localiza retrabalho
      $.ajax({
        url: dadosRetrabalhoUrl + data.retrabalho_id,
        type: 'GET',
        success: function (data) {
          //dados do retrabalho
          $('#idretrabalho').val(data.id);
          $('#observacoes').val(data.observacoes);
          $('#idcliente').val(data.cliente_id);
          $('#idcliente').trigger('change');
          //itens
          if (data.itens) {
            data.itens.map(function (item, index) {
              let row = $('.item-retrabalho').last();
              row.find('select[name*=idtiposervico]').val(item.idtiposervico);
              row.find('select[name*=idmaterial]').val(item.idmaterial);
              row.find('input[name*=milesimos]').val(item.milesimos);
              row.find('input[name*=peso]').val(item.peso);
              row.find('input[name*=tipo_falha_id]').val(item.tipo_falha_id);
              row.find('input[name*=item_id]').val(item.id);
              if (item.idmaterial) {
                let cbCores = row.find('select[name*=idcor]');
                $.when(preencheCores(item.idmaterial, cbCores)).done(function () {
                  row.find('select[name*=idcor]').val(item.idcor);
                });
              }

              //Adiciona linha
              row.find('.btn-add-item-retrabalho').trigger('click');
            });
          }

          //modal
          $('#retrabalho-modal').modal('show');
        },
        error: function (jqXHR, textStatus, err) {
          console.log(jqXHR);
          window.toastr.error(jqXHR.responseText)
        }
      });
    },
    error: function (jqXHR, textStatus, err) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseText)
    }
  });
}

//Iniciar Preparação
function iniciarPreparacao(id) {
  $.ajax({
    url: iniciarPreparacaoUrl,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    type: 'POST',
    data: {
      'id': id
    },
    success: function (data) {
      refreshColumn('F');
    },
    error: function (jqXHR, textStatus, error) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Iniciar Retrabalho
function iniciarRetrabalho(id) {
  $.ajax({
    url: iniciarRetrabalhoUrl,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    type: 'POST',
    data: {
      'id': id
    },
    success: function (data) {
      refreshColumn('T');
    },
    error: function (jqXHR, textStatus, error) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Iniciar Expedição
function iniciarExpedicao(id) {
  $.ajax({
    url: iniciarExpedicaoUrl,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    type: 'POST',
    data: {
      'id': id
    },
    success: function (data) {
      refreshColumn('C');
    },
    error: function (jqXHR, textStatus, error) {
      console.log(jqXHR);
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Atualiza a coluna informada
function refreshColumn(col) {
  let container = null;
  switch (col) {
    case 'R':
      container = $('#recebimentos');
      break;
    case 'S':
      container = $('#separacoes');
      break;
    case 'A':
      container = $('#catalogacoes');
      break;
    case 'F':
      container = $('#preparacoes');
      break;
    case 'B':
      container = $('#banhos');
      break;
    case 'T':
      container = $('#retrabalhos');
      break;
    case 'G':
      container = $('#revisoes');
      break;
    case 'C':
      container = $('#expedicoes');
      break;
    case 'L':
      container = $('#concluidos');
      break;
    default:
      break;
  }

  $.ajax({
    url: panelColumnUrl,
    type: 'GET',
    data: {
      'col': col
    },
    success: function (data) {
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
    error: function (jqXHR, textStatus, error) {
      window.toastr.error(jqXHR.responseJSON.message)
    }
  });
}

//Filtra resultados de acordo com pesquisa
function filtrar() {
  let term = $('#search').val();

  if (term != '') {
    $('.card').addClass('d-none');
  } else {
    $('.card').removeClass('d-none');
  }

  $(".text-body:icontains('" + term + "')").parent().parent().parent().removeClass('d-none');
  calcTotais();
}

//Calcula totais das colunas
function calcTotais() {
  $('.tasks').each(function (i, coluna) {
    let peso_coluna = 0;
    let qtde_cards = 0;
    $(coluna).find('.card').not('.d-none').each(function (j, bloco) {
      //Peso
      peso_bloco = parseInt($(bloco).find('.badge-peso').html().replace(' g', '').replace(/\./g, ''));
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
    $(coluna).find('.totalizador-peso').html(peso_coluna);
    //Itens da coluna
    $(coluna).find('.totalizador').html(qtde_cards);
  });
}

//Limpa campos modal retrabalho
function limpaCamposRetrabalho() {
  $('#idseparacao').val('');
  $('#idretrabalho').val('');
  $('#idcliente').val('');
  $('#idcliente').trigger('change');
  $('#observacoes').val('');
  let lim = $('.item-retrabalho').length - 1;
  $('.item-retrabalho').each(function (i, v) {
    if (i < lim) {
      $(v).remove();
    } else {
      $(v).find('input, select').each(function (j, elem) {
        $(elem).val('');
      });
      $(v).attr('data-index', '0');
    }
  });
}

//Preenche Cores
function preencheCores(id, cbCores) {
  return $.ajax({
    url: coresUrl + id,
    dataType: "json",
    success: function (data) {
      cbCores.empty();
      cbCores.append(`<option></option>`);

      for (let k in data) {
        cbCores.append(`<option value="${data[k].id}">${data[k].descricao}</option>`);
      }
    }
  });
}

//Pesquisa
$(document).on('keyup', '#search', function (key) {
  filtrar();
});

//Arquivar
$(document).on('click', '.action-arquivar', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  arquivarRecebimento(id);
});

//Encerrar Separação
$(document).on('click', '.action-encerrar-sep', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  encerrarSeparacao(id);
});

//Encerrar Retrabalho
$(document).on('click', '.action-encerrar-retrab', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  encerrarRetrabalho(id);
});

//Iniciar preparação
$(document).on('click', '.action-iniciar-prep', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  iniciarPreparacao(id);
});

//Iniciar Retrabalho
$(document).on('click', '.action-iniciar-retrab', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  iniciarRetrabalho(id);
});

//Iniciar Expedição
$(document).on('click', '.action-iniciar-exped', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  iniciarExpedicao(id);
});

//Editar Retrabalho
$(document).on('click', '.edit-retrabalho', function (event) {
  let id = $(this).parent().parent().parent().parent().data('id');
  editarRetrabalho(id);
});

//Add Item Retrabalho
$(document).on('click', '.btn-add-item-retrabalho', function () {
  let rowbase = $('.item-retrabalho').last();
  let container = rowbase.parent();
  let clone = rowbase.clone();
  let index = parseInt(clone.data('index'));
  let cloneindex = index + 1;

  clone.attr('data-index', cloneindex);
  clone.find('input, select').each(function (i, elem) {
    $(elem).attr('name', $(elem).attr('name').replace(index, cloneindex));
    $(elem).val('');
  });

  rowbase.find('.item-retrabalho-controls').removeClass('d-none').addClass('d-flex');
  rowbase.find('.btn-add-item-retrabalho').addClass('d-none');

  container.append(clone);
});

//Del Item Retrabalho
$(document).on('click', '.btn-remove-item-retrabalho', function (e) {
  e.preventDefault();
  let row = $(this).parent().parent().parent();
  row.remove();

});

$(document).on('change', 'select[name*="idmaterial"]', function () {
  let id = $(this).val();
  let cbCores = $(this).parent().parent().find('select[name*="idcor"]');

  if (id == '') {
    cbCores.empty();
    cbCores.append(`<option></option>`);
  } else {
    preencheCores(id, cbCores);
  }
});

$(document).on('click', '#btn-registrar', function () {

  let data = $('#retrabalho-form').serializeArray();

  $.ajax({
    url: storeRetrabalhoUrl,
    type: 'POST',
    data: data,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    success: function (data) {
      $('#retrabalho-modal').modal('hide');
      refreshColumn('T');
    }
  });

});

//Prevent retrabalho form submit
$(document).on('submit', '#retrabalho-form', function (e) {
  e.preventDefault();
});
