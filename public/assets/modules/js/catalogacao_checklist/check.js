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

  // Toggle border on check
  (function () {
    (0, _jquery2.default)('input[type=radio]').change(function () {
      if (this.value == 'true') {
        (0, _jquery2.default)(this).parent().parent().addClass('border-success');
        (0, _jquery2.default)(this).parent().parent().removeClass('border-danger');
      } else if (this.value == 'false') {
        (0, _jquery2.default)(this).parent().parent().removeClass('border-success');
        (0, _jquery2.default)(this).parent().parent().addClass('border-danger');
      }
    });
  })();
});