(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/cotacoes/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.cotacoesIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  //Force tipopessoa change
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      (0, _jquery2.default)('#add-data').mask('00/00/0000 00:00');
      (0, _jquery2.default)('#add-valorg').mask('#.##0,00', { reverse: true });
    });
  })();
});