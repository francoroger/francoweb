(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/camera', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginCamera = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'camera';

  function getCameraAPI($el) {
    if ($el.length <= 0) {
      return;
    }
    var api = $el.data('cameraAPI');

    if (api) {
      return api;
    }

    api = new Camera($el, _jquery2.default.extend(true, {}, Camera.getDefaults(), $el.data()));
    api.render();
    return api;
  }

  var Camera = function (_Plugin) {
    babelHelpers.inherits(Camera, _Plugin);

    function Camera() {
      babelHelpers.classCallCheck(this, Camera);
      return babelHelpers.possibleConstructorReturn(this, (Camera.__proto__ || Object.getPrototypeOf(Camera)).apply(this, arguments));
    }

    babelHelpers.createClass(Camera, [{
      key: 'getName',
      value: function getName() {
        return;
      }
    }, {
      key: 'render',
      value: function render(context) {
        var $el = this.$el;

        this.isEnabled = false;
        this.isFreezed = false;

        this.$preview = $el.find('.preview-img');
        this.$cam = $el.find('.live-cam');
        this.$indicator = $el.find('[data-action="camera-toggle-enable"]');
        this.$background = $el.find('.overlay-background');

        var w = (0, _jquery2.default)(this.$preview).width();
        var h = (0, _jquery2.default)(this.$preview).height();

        this.$cam.width(w);
        this.$cam.height(h);
        this.$cam.addClass('d-none');

        $el.data('cameraAPI', this);
      }
    }, {
      key: 'toggleEnable',
      value: function toggleEnable() {
        if (this.isEnabled) {
          this.turnOff();
        } else {
          this.turnOn();
        }
      }
    }, {
      key: 'toggleFreeze',
      value: function toggleFreeze() {
        if (this.isFreezed) {
          this.unfreeze();
        } else {
          this.freeze();
        }
      }
    }, {
      key: 'turnOn',
      value: function turnOn() {
        if (this.isEnabled !== true) {
          this.$preview.addClass('d-none');
          this.$cam.removeClass('d-none');
          this.$indicator.addClass('active');
          this.$indicator.addClass('focus');

          Webcam.attach(this.$cam[0]);

          this.isEnabled = true;
        }
      }
    }, {
      key: 'turnOff',
      value: function turnOff() {
        if (this.isEnabled !== false) {
          this.$preview.removeClass('d-none');
          this.$cam.addClass('d-none');
          this.$indicator.removeClass('active');
          this.$indicator.removeClass('focus');

          Webcam.reset();

          this.isEnabled = false;
        }
      }
    }, {
      key: 'takeSnapshot',
      value: function takeSnapshot() {
        var _this2 = this;

        if (this.isEnabled === true) {
          Webcam.snap(function (data_uri) {
            _this2.$preview.attr('src', data_uri);
            _this2.$background.addClass('d-none');
            _this2.turnOff();
          });
        }
      }
    }, {
      key: 'freeze',
      value: function freeze() {
        if (this.isEnabled === true && this.isFreezed !== true) {
          Webcam.freeze();
          this.isFreezed = true;
        }
      }
    }, {
      key: 'unfreeze',
      value: function unfreeze() {
        if (this.isEnabled === true && this.isFreezed === true) {
          Webcam.unfreeze();
          this.isFreezed = false;
        }
      }
    }, {
      key: 'upload',
      value: function upload() {
        console.log('upload');
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }, {
      key: 'api',
      value: function api() {
        return function () {
          (0, _jquery2.default)(document).on('click', '[data-action="camera-toggle-enable"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.toggleEnable();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="camera-toggle-freeze"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.toggleFreeze();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="camera-on"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.turnOn();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="camera-off"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.turnOff();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="camera-freeze"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.freeze();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="camera-unfreeze"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.unfreeze();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="camera-snapshot"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.takeSnapshot();
          });

          (0, _jquery2.default)(document).on('click', '[data-action="image-upload"]', function (e) {
            e.preventDefault();
            var api = getCameraAPI((0, _jquery2.default)(this).closest('.panel'));
            api.upload();
          });
        };
      }
    }]);
    return Camera;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Camera);

  exports.default = Camera;
});