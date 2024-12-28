(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/clientes/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.clientesIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  var tbClientes;

  window.removeTableRow = function (row) {
    tbClientes.row(row).remove().draw();
  };

  // Tabela Clientes
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'nome' }, { data: 'cpf' }, { data: 'cidade' }, { data: 'uf' }, { data: 'telefone' }, { data: 'status', sClass: "text-center" }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }]
      });

      tbClientes = (0, _jquery2.default)('#clientes-table').DataTable(options);
    });
  })();

  // Excluir Cliente
  (function () {
    (0, _jquery2.default)(document).on("click", '.btn-delete', function () {
      var id = this.getAttribute('data-id');
      swal({
        title: "Excluir",
        text: "Deseja realmente excluir este cliente?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true
      }, function () {
        excluirCliente(id);
      });
    });
  })();
});