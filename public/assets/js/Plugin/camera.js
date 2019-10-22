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
        var _this2 = this;

        var $el = this.$el;

        this.isEnabled = false;
        this.isFreezed = false;

        this.$preview = $el.find('.preview-img');
        this.$cam = $el.find('.live-cam');
        this.$indicator = $el.find('[data-action="camera-toggle-enable"]');
        this.$background = $el.find('.overlay-background');
        this.$ajax_url = $el.data('url');
        this.$token = $el.data('token');
        this.$form_elem_name = 'snapshot';
        this.$uploaded_file = $el.find('input[name="uploaded-file"]');

        var w = (0, _jquery2.default)(this.$preview).width();
        var h = (0, _jquery2.default)(this.$preview).height();

        this.$cam.width(w);
        this.$cam.height(h);
        this.$cam.addClass('d-none');

        $el.data('cameraAPI', this);

        //Webcam events
        Webcam.on('uploadProgress', function (progress) {
          // Upload in progress
          // 'progress' will be between 0.0 and 1.0
        });

        Webcam.on('uploadComplete', function (code, text) {
          if (code == 200) {
            var json = JSON.parse(text);
            _this2.$uploaded_file.val(json.filename);
            _this2.$preview.attr('src', json.path);
            _this2.$background.addClass('d-none');
            _this2.turnOff();
          }
        });
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
        var _this3 = this;

        if (this.isEnabled === true) {
          Webcam.snap(function (data_uri) {
            _this3.$preview.attr('src', data_uri);
            _this3.$background.addClass('d-none');
            _this3.turnOff();
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
        var _this4 = this;

        if (this.isEnabled === true && this.$ajax_url) {
          Webcam.snap(function (data_uri) {
            // detect image format from within image_data_uri
            var image_fmt = '';
            if (data_uri.match(/^data\:image\/(\w+)/)) image_fmt = RegExp.$1;else throw "Cannot locate image format in Data URI";

            // contruct use AJAX object
            var http = new XMLHttpRequest();
            http.open("POST", _this4.$ajax_url, true);
            http.setRequestHeader('X-CSRF-TOKEN', _this4.$token);

            // setup progress events
            if (http.upload && http.upload.addEventListener) {
              http.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                  var progress = e.loaded / e.total;
                  Webcam.dispatch('uploadProgress', progress, e);
                }
              }, false);
            }

            // completion handler
            http.onload = function () {
              Webcam.dispatch('uploadComplete', http.status, http.responseText, http.statusText);
            };

            // extract raw base64 data from Data URI
            var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');

            // create a blob and decode our base64 to binary
            var blob = new Blob([_this4._base64DecToArr(raw_image_data)], { type: 'image/' + image_fmt });

            // stuff into a form, so servers can easily receive it as a standard file upload
            var form = new FormData();
            form.append(_this4.$form_elem_name, blob, _this4.$form_elem_name + "." + image_fmt.replace(/e/, ''));
            form.append('data_uri', data_uri);
            form.append('image_fmt', image_fmt);

            // send data to server
            http.send(form);
          });
        }
      }
    }, {
      key: '_b64ToUint6',
      value: function _b64ToUint6(nChr) {
        // convert base64 encoded character to 6-bit integer
        // from: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Base64_encoding_and_decoding
        return nChr > 64 && nChr < 91 ? nChr - 65 : nChr > 96 && nChr < 123 ? nChr - 71 : nChr > 47 && nChr < 58 ? nChr + 4 : nChr === 43 ? 62 : nChr === 47 ? 63 : 0;
      }
    }, {
      key: '_base64DecToArr',
      value: function _base64DecToArr(sBase64, nBlocksSize) {
        // convert base64 encoded string to Uintarray
        // from: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Base64_encoding_and_decoding
        var sB64Enc = sBase64.replace(/[^A-Za-z0-9\+\/]/g, ""),
            nInLen = sB64Enc.length,
            nOutLen = nBlocksSize ? Math.ceil((nInLen * 3 + 1 >> 2) / nBlocksSize) * nBlocksSize : nInLen * 3 + 1 >> 2,
            taBytes = new Uint8Array(nOutLen);

        for (var nMod3, nMod4, nUint24 = 0, nOutIdx = 0, nInIdx = 0; nInIdx < nInLen; nInIdx++) {
          nMod4 = nInIdx & 3;
          nUint24 |= this._b64ToUint6(sB64Enc.charCodeAt(nInIdx)) << 18 - 6 * nMod4;
          if (nMod4 === 3 || nInLen - nInIdx === 1) {
            for (nMod3 = 0; nMod3 < 3 && nOutIdx < nOutLen; nMod3++, nOutIdx++) {
              taBytes[nOutIdx] = nUint24 >>> (16 >>> nMod3 & 24) & 255;
            }
            nUint24 = 0;
          }
        }
        return taBytes;
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

          (0, _jquery2.default)(document).on('click', '[data-action="camera-upload"]', function (e) {
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