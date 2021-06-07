import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function ($) {
  Site.run();

  $('#idcliente').select2();
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
