import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbMateriais;

window.removeTableRow = function(row) {
  tbMateriais.row(row).remove().draw();
};

// Tabela Materiais
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'descricao' },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ]
    });

    tbMateriais = $('#materiais-table').DataTable(options);
  });
})();

// Excluir Material
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este material?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirMaterial(id);
      });
  });
})();
