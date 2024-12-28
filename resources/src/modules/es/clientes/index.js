import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

var tbClientes;

window.removeTableRow = function(row) {
  tbClientes.row(row).remove().draw();
};

// Tabela Clientes
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

    tbClientes = $('#clientes-table').DataTable(options);
  });
})();

// Excluir Cliente
(function() {
  $(document).on("click", '.btn-delete', function() {
    var id = this.getAttribute('data-id');
    swal({
        title: "Excluir",
        text: "Deseja realmente excluir este cliente?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar',
        closeOnConfirm: true,
      },
      function() {
        excluirCliente(id);
      });
  });
})();
