(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/tanques/edit', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.tanquesEdit = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  (function () {
    (0, _jquery2.default)(document).ready(function () {
      (0, _jquery2.default)('#desconto_milesimo').mask('#.##0,00', { reverse: true });
    });
  })();
});