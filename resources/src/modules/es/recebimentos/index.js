import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbRecebimentos;

window.removeTableRow = function(row) {
  tbRecebimentos.row(row).remove().draw();
};

// Tabela Recebimentos
// ---------------------------
(function() {
  $(document).ready(function() {
    var defaults = Plugin.getDefaults("dataTable");

    var options = $.extend(true, {}, defaults, {
      columns: [
        { data: 'data_hora' },
        { data: 'cliente' },
        { data: 'fornecedor' },
        { data: 'pesototal' },
        { data: 'responsavel' },
        { data: 'status', sClass: "text-center" },
        { data: 'actions', sClass: "text-center", orderable: false, searchable: false }
      ],
      columnDefs: [
        {targets: 0, render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm')}
      ],
      order: [[ 0, 'desc' ]],
    });

    tbRecebimentos = $('#recebimentos-table').DataTable(options);
  });
})();

// Excluir Recebimento
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este recebimento?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirRecebimento(id);
      });
  });
})();
