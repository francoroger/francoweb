(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/tanques/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.tanquesIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  var tbTanques;

  window.removeTableRow = function (row) {
    tbTanques.row(row).remove().draw();
  };

  // Tabela Tanques
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'descricao' }, { data: 'ciclo_reforco' }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }]
      });

      tbTanques = (0, _jquery2.default)('#tanques-table').DataTable(options);
    });
  })();

  // Excluir Tanque
  (function () {
    (0, _jquery2.default)(document).on("click", '.btn-delete', function () {
      var id = this.getAttribute('data-id');
      swal({
        title: "Excluir",
        text: "Deseja realmente excluir este tanque?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true
      }, function () {
        excluirTanque(id);
      });
    });
  })();
});