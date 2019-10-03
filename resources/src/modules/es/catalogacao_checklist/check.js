import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

// Filter
(function() {
  $(document).on('click', '.dropdown-item', function() {
    $(this).parent().parent().find('.selected-item').html($(this).text());

    $(this).parent()
      .find('.dropdown-item.active')
      .each(function () {
        $(this).attr('aria-expanded', false)
          .removeClass('active')
      });

    $(this).addClass('active').attr('aria-expanded', true);

    let filters = [];
    $('.dropdown-item.active').each(function(i,v) {
      let filter = $(v).attr('data-filter');
      if (filter !== '*') {
        filters.push('.'+filter );
      }
    });
    let selector = filters.join('');

    $('#itens_catalogo').isotope({
      filter: selector
    });
  });
})();

// Toggle border on check
(function() {
  $('input[type=radio]').change(function() {
    $(this).parent().parent().removeClass('border-success');
    $(this).parent().parent().removeClass('border-danger');

    if (this.value == 'S') {
      $(this).parent().parent().addClass('border-success');
    }
    else if (this.value == 'N') {
      $(this).parent().parent().addClass('border-danger');
    }
  });
})();

// Alert on submit
(function() {
  $("#check-form").submit(function(event) {
    event.preventDefault();
    swal({
      title: "Finalizar Verificação",
      text: "A catalogação está completamente verificada?",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-success",
      //cancelButtonClass: "btn-danger",
      confirmButtonText: 'Sim, Finalizar!',
      cancelButtonText: 'Não',
      closeOnConfirm: true,
    },
    function(isConfirm) {
      if (isConfirm) {
        $('#status').val('C');
      } else {
        $('#status').val('P');
      }
      $("#check-form").unbind('submit').submit();
    });
  });
})();
