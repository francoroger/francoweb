(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/catalogacao_checklist/checklist', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.catalogacao_checklistChecklist = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  // Confirmar catalogação
  (function () {
    (0, _jquery2.default)(document).on("click", '.send', function (event) {
      event.preventDefault();
      swal({
        title: "Confirmar",
        text: "A catalogação está totalmente concluída?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: 'Sim, Concluída!',
        cancelButtonText: 'Não, Parcial.',
        closeOnConfirm: true
      }, function () {
        console.log('teste');
      });
    });
  })();
});