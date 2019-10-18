(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/webcam', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginWebcam = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'webcam';

  var Webcam = function (_Plugin) {
    babelHelpers.inherits(Webcam, _Plugin);

    function Webcam() {
      babelHelpers.classCallCheck(this, Webcam);
      return babelHelpers.possibleConstructorReturn(this, (Webcam.__proto__ || Object.getPrototypeOf(Webcam)).apply(this, arguments));
    }

    babelHelpers.createClass(Webcam, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        Webcam.attach(this.$el);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          width: 320,
          height: 240,
          dest_width: 640,
          dest_height: 480,
          image_format: 'jpeg',
          jpeg_quality: 90,
          force_flash: false
        };
      }
    }]);
    return Webcam;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Webcam);

  exports.default = Webcam;
});