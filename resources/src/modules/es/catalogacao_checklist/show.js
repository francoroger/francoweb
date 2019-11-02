import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();

  $('.image-wrap').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    mainClass: 'mfp-fade',
    tClose: 'Fechar (Esc)',
    tLoading: 'Carregando...',
    gallery: {
      enabled: false
    }
  });
});

var grid;

// Filter
(function() {
  $(document).on('click', '.dropdown-item', function() {
    let btn = $(this).parent().parent();

    btn.find('.selected-item').html($(this).text());

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

      //Adiciona no campo de filtros para futura impressão filtrada
      let ftype = $(v).attr('data-filter-type');
      let fvalue = $(v).attr('data-filter-value');
      $('input[name="'+ftype+'"]').val(fvalue);

      if (filter !== '*') {
        filters.push('.'+filter );
      }
    });
    let selector = filters.join('');

    grid = $('#itens_catalogo').isotope({
      filter: selector
    });

  });
})();
