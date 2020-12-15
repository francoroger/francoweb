(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/relatorios/servicos', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.relatoriosServicos = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  //Modelo Change
  (function () {
    (0, _jquery2.default)(document).on('change', '#modelo', function (event) {
      if ((0, _jquery2.default)(this).val() == 'D') {
        (0, _jquery2.default)('#sorter-det').removeClass('d-none');
        (0, _jquery2.default)('#sorter-res').addClass('d-none');
        (0, _jquery2.default)('#group-by').addClass('d-none');
      } else if ((0, _jquery2.default)(this).val() == 'R') {
        (0, _jquery2.default)('#sorter-det').addClass('d-none');
        (0, _jquery2.default)('#sorter-res').removeClass('d-none');
        (0, _jquery2.default)('#group-by').addClass('d-none');
      } else if ((0, _jquery2.default)(this).val() == 'A') {
        (0, _jquery2.default)('#sorter-det').removeClass('d-none');
        (0, _jquery2.default)('#sorter-res').addClass('d-none');
        (0, _jquery2.default)('#group-by').removeClass('d-none');
      }
    });
  })();

  // Fetch Data
  window.fetchData = function (route, token, page) {
    var formData = new FormData();
    formData.append('dataini', (0, _jquery2.default)('#dataini').val());
    formData.append('datafim', (0, _jquery2.default)('#datafim').val());
    formData.append('idcliente', (0, _jquery2.default)('#idcliente').val().toString());
    formData.append('idguia', (0, _jquery2.default)('#idguia').val().toString());
    formData.append('idtiposervico', (0, _jquery2.default)('#idtiposervico').val().toString());
    formData.append('idmaterial', (0, _jquery2.default)('#idmaterial').val().toString());
    formData.append('idcor', (0, _jquery2.default)('#idcor').val().toString());
    formData.append('milini', (0, _jquery2.default)('#milini').val().toString());
    formData.append('milfim', (0, _jquery2.default)('#milfim').val().toString());
    formData.append('modelo', (0, _jquery2.default)('#modelo').val().toString());
    formData.append('sortbydet', (0, _jquery2.default)('#sortbydet').val());
    formData.append('sortbyres', (0, _jquery2.default)('#sortbyres').val());
    formData.append('grupos', (0, _jquery2.default)('#grupos').val());

    route += page ? "page=" + page : '';

    return _jquery2.default.ajax({
      url: route,
      headers: { 'X-CSRF-TOKEN': token },
      type: 'POST',
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      success: function success(data) {
        (0, _jquery2.default)('#result').html(data.view);
        var el = (0, _jquery2.default)("#result");
        (0, _jquery2.default)('html,body').animate({ scrollTop: el.offset().top - 80 }, 'slow');
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        alert('erro');
        console.log(jqXHR);
      }
    });
  };
});