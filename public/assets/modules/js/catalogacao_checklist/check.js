(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/catalogacao_checklist/check', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.catalogacao_checklistCheck = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  // Filter
  (function () {
    (0, _jquery2.default)(document).on('click', '.dropdown-item', function () {
      (0, _jquery2.default)(this).parent().parent().find('.selected-item').html((0, _jquery2.default)(this).text());

      (0, _jquery2.default)(this).parent().find('.dropdown-item.active').each(function () {
        (0, _jquery2.default)(this).attr('aria-expanded', false).removeClass('active');
      });

      (0, _jquery2.default)(this).addClass('active').attr('aria-expanded', true);

      var filters = [];
      (0, _jquery2.default)('.dropdown-item.active').each(function (i, v) {
        var filter = (0, _jquery2.default)(v).attr('data-filter');
        if (filter !== '*') {
          filters.push('.' + filter);
        }
      });
      var selector = filters.join('');

      (0, _jquery2.default)('#itens_catalogo').isotope({
        filter: selector
      });
    });
  })();

  // Toggle border on check
  (function () {
    (0, _jquery2.default)('input[type=radio]').change(function () {
      (0, _jquery2.default)(this).parent().parent().removeClass('border-success');
      (0, _jquery2.default)(this).parent().parent().removeClass('border-danger');

      if (this.value == 'S') {
        (0, _jquery2.default)(this).parent().parent().addClass('border-success');
      } else if (this.value == 'N') {
        (0, _jquery2.default)(this).parent().parent().addClass('border-danger');
      }
    });
  })();

  // Alert on submit
  (function () {
    (0, _jquery2.default)("#check-form").submit(function (event) {
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
        closeOnConfirm: true
      }, function (isConfirm) {
        if (isConfirm) {
          (0, _jquery2.default)('#status').val('C');
        } else {
          (0, _jquery2.default)('#status').val('P');
        }
        (0, _jquery2.default)("#check-form").unbind('submit').submit();
      });
    });
  })();
});