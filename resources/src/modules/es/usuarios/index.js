import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbUsuarios;

window.removeTableRow = function(row) {
  tbUsuarios.row(row).remove().draw();
};

// Tabela Usuários
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'name' },
        { data: 'email' },
        { data: 'role' },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ]
    });

    tbUsuarios = $('#usuarios-table').DataTable(options);
  });
})();

// Excluir Usuário
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este usuário?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirUsuario(id);
      });
  });
})();
