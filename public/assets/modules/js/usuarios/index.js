(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/usuarios/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.usuariosIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  // Tabela Usuários
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'name' }, { data: 'email' }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }]
      });

      (0, _jquery2.default)('#usuarios-table').DataTable(options);
    });
  })();

  // Excluir Usuário
  (function () {
    (0, _jquery2.default)(document).on("click", '.btn-delete', function () {
      var id = this.getAttribute('data-id');
      swal({
        title: "Excluir",
        text: "Deseja realmente excluir este usuário?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true
      }, function () {
        excluirUsuario(id);
      });
    });
  })();
});