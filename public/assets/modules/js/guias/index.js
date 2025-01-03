(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/guias/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.guiasIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  var tbGuias;

  window.removeTableRow = function (row) {
    tbGuias.row(row).remove().draw();
  };

  // Tabela Guias
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'nome' }, { data: 'cpf' }, { data: 'cidade' }, { data: 'uf' }, { data: 'telefone' }, { data: 'status', sClass: "text-center" }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }]
      });

      tbGuias = (0, _jquery2.default)('#guias-table').DataTable(options);
    });
  })();

  // Excluir Guia
  (function () {
    (0, _jquery2.default)(document).on("click", '.btn-delete', function () {
      var id = this.getAttribute('data-id');
      swal({
        title: "Excluir",
        text: "Deseja realmente excluir este guia?",
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