import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

// Confirmar catalogação
(function() {
  $(document).on("click", '.send', function(event) {
    event.preventDefault();
    swal({
      title: "Confirmar",
      text: "A catalogação está totalmente concluída?",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-success",
      confirmButtonText: 'Sim, Concluída!',
      cancelButtonText: 'Não, Parcial.',
      closeOnConfirm: true,
      },
      function() {
        console.log('teste');
      });
  });
})();
