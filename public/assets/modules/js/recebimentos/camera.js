(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/recebimentos/camera', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.recebimentosCamera = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();

    var $camera1 = $$$1('#capture_cam');
    var CameraClass1 = new Plugin.getPlugin('camera');
    var api1 = new CameraClass1($camera1, $camera1.data());
    api1.render();

    $$$1('#btn-capturar').on('click', function () {
      api1.upload(function (fname, fpath) {
        $$$1('#capturaModal').modal('hide');

        var elem = '<li>\n        <div class="panel">\n          <figure class="overlay overlay-hover animation-hover">\n            <img class="caption-figure overlay-figure" src="' + fpath + '">\n            <input type="hidden" name="fotos[]" value="' + fname + '">\n            <figcaption class="overlay-panel overlay-background overlay-fade text-center vertical-align">\n              <div class="btn-group">\n                <button type="button" class="btn btn-icon btn-pure btn-default btn-delete" title="Excluir" data-id="">\n                  <i class="icon wb-trash"></i>\n                </button>\n              </div>\n            </figcaption>\n          </figure>\n        </div>\n      </li>';

        $$$1('#foto-container').append(elem);
      });
    });

    $$$1('#capturaModal').on('shown.bs.modal', function (e) {
      api1.turnOn();
    });

    $$$1('#capturaModal').on('hide.bs.modal', function (e) {
      api1.turnOff();
    });
  });
});