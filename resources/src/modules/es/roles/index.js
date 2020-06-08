import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbRoles;

window.removeTableRow = function(row) {
  tbRoles.row(row).remove().draw();
};

// Tabela Roles
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'name' },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ]
    });

    tbRoles = $('#roles-table').DataTable(options);
  });
})();

// Excluir Role
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este perfil?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirRole(id);
      });
  });
})();
