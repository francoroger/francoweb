import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbGuias;

window.removeTableRow = function(row) {
  tbGuias.row(row).remove().draw();
};

// Tabela Guias
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'nome' },
        { data: 'cpf' },
        { data: 'cidade' },
        { data: 'uf' },
        { data: 'telefone' },
        { data: 'status', sClass: "text-center" },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ]
    });

    tbGuias = $('#guias-table').DataTable(options);
  });
})();

// Excluir Guia
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este guia?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirGuia(id);
      });
  });
})();
