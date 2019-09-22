(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/catalogacao_checklist/index', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.catalogacao_checklistIndex = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  // Tabela Check List Catalogação
  // ---------------------------
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      var defaults = Plugin.getDefaults("dataTable");

      var options = _jquery2.default.extend(true, {}, defaults, {
        columns: [{ data: 'id' }, { data: 'cliente' }, { data: 'datacad' }, { data: 'horacad' }, { data: 'status', sClass: "text-center" }, { data: 'actions', sClass: "text-center", orderable: false, searchable: false }],
        order: [[0, 'desc']]
      });

      (0, _jquery2.default)('#catalogacao-checklist-table').DataTable(options);
    });
  })();
});