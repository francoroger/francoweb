(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/retrabalhos/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.retrabalhosIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  var tbRetrabalhos;

  window.removeTableRow = function (row) {
    tbRetrabalhos.row(row).remove().draw();
  };

  // Tabela Retrabalhos
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'id' }, { data: 'nome' }, { data: 'datacad' }, { data: 'status', sClass: "text-center" }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }],
        order: [[0, "desc"]]
      });

      tbRetrabalhos = (0, _jquery2.default)('#retrabalhos-table').DataTable(options);
    });
  })();

  // Excluir Retrabalho
  (function () {
    (0, _jquery2.default)(document).on("click", '.btn-delete', function () {
      var id = this.getAttribute('data-id');
      swal({
        title: "Excluir",
        text: "Deseja realmente excluir este retrabalho?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true
      }, function () {
        excluirGuia(id);
      });
    });
  })();
});