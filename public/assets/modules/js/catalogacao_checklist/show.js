(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/catalogacao_checklist/show', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.catalogacao_checklistShow = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();

    $$$1('.image-wrap').magnificPopup({
      type: 'image',
      closeOnContentClick: true,
      mainClass: 'mfp-fade',
      tClose: 'Fechar (Esc)',
      tLoading: 'Carregando...',
      gallery: {
        enabled: false
      }
    });

    $$$1('.dropdown-item.selected-item').trigger('click');
  });

  var grid;

  // Filter
  (function () {
    (0, _jquery2.default)(document).on('click', '.dropdown-item', function () {
      var btn = (0, _jquery2.default)(this).parent().parent();

      btn.find('.selected-item').html((0, _jquery2.default)(this).text());

      (0, _jquery2.default)(this).parent().find('.dropdown-item.active').each(function () {
        (0, _jquery2.default)(this).attr('aria-expanded', false).removeClass('active');
      });

      (0, _jquery2.default)(this).addClass('active').attr('aria-expanded', true);

      var filters = [];
      (0, _jquery2.default)('.dropdown-item.active').each(function (i, v) {
        var filter = (0, _jquery2.default)(v).attr('data-filter');

        //Adiciona no campo de filtros para futura impressão filtrada
        var ftype = (0, _jquery2.default)(v).attr('data-filter-type');
        var fvalue = (0, _jquery2.default)(v).attr('data-filter-value');
        (0, _jquery2.default)('input[name="' + ftype + '"]').val(fvalue);

        if (filter !== '*') {
          filters.push('.' + filter);
        }
      });
      var selector = filters.join('');

      grid = (0, _jquery2.default)('#itens_catalogo').isotope({
        filter: selector
      });
    });
  })();
});