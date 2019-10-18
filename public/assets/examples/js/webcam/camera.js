(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/webcam/camera', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.webcamCamera = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();

    var $camera1 = $$$1('#exampleCamera1');
    var CameraClass1 = new Plugin.getPlugin('camera');
    var api1 = new CameraClass1($camera1, $camera1.data());
    api1.render();
  });
});