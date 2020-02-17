import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbTanques;

window.removeTableRow = function(row) {
  tbTanques.row(row).remove().draw();
};

// Tabela Tanques
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'descricao' },
        { data: 'ciclo_reforco' },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ]
    });

    tbTanques = $('#tanques-table').DataTable(options);
  });
})();

// Excluir Tanque
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este tanque?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirTanque(id);
      });
  });
})();
