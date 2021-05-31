import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function ($) {
  Site.run();
});

var tbRetrabalhos;

window.removeTableRow = function (row) {
  tbRetrabalhos.row(row).remove().draw();
};

// Tabela Retrabalhos
// ---------------------------
(function () {
  $(document).ready(function () {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'id' },
        { data: 'nome' },
        { data: 'datacad' },
        { data: 'status', sClass: "text-center" },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ],
      order: [[0, "desc"]],
    });

    tbRetrabalhos = $('#retrabalhos-table').DataTable(options);
  });
})();

// Excluir Retrabalho
(function () {
  $(document).on("click", '.btn-delete', function () {
    var id = this.getAttribute('data-id');
    swal({
      title: "Excluir",
      text: "Deseja realmente excluir este retrabalho?",
      type: "error",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: 'Sim',
      cancelButtonText: 'Cancelar',
      closeOnConfirm: true,
    },
      function () {
        excluirGuia(id);
      });
  });
})();
