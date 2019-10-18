(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/recebimentos/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.recebimentosIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  var tbRecebimentos;

  window.removeTableRow = function (row) {
    tbRecebimentos.row(row).remove().draw();
  };

  // Tabela Recebimentos
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'data_hora' }, { data: 'cliente' }, { data: 'fornecedor' }, { data: 'pesototal' }, { data: 'responsavel' }, { data: 'status', sClass: "text-center" }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }],
        columnDefs: [{ targets: 0, render: _jquery2.default.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm') }],
        order: [[0, 'desc']]
      });

      tbRecebimentos = (0, _jquery2.default)('#recebimentos-table').DataTable(options);
    });
  })();

  // Excluir Recebimento
  (function () {
    (0, _jquery2.default)(document).on("click", '.btn-delete', function () {
      var id = this.getAttribute('data-id');
      swal({
        title: "Excluir",
        text: "Deseja realmente excluir este recebimento?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true
      }, function () {
        excluirRecebimento(id);
      });
    });
  })();
});