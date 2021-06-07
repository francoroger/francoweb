(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/retrabalhos/edit', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.retrabalhosEdit = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();

    $$$1('#idcliente').select2();
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
});