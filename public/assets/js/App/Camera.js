(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Camera', ['exports', 'BaseApp'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('BaseApp'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.BaseApp);
    global.AppCamera = mod.exports;
  }
})(this, function (exports, _BaseApp2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppCamera = undefined;

  var _BaseApp3 = babelHelpers.interopRequireDefault(_BaseApp2);

  var AppCamera = function (_BaseApp) {
    babelHelpers.inherits(AppCamera, _BaseApp);

    function AppCamera() {
      babelHelpers.classCallCheck(this, AppCamera);
      return babelHelpers.possibleConstructorReturn(this, (AppCamera.__proto__ || Object.getPrototypeOf(AppCamera)).apply(this, arguments));
    }

    babelHelpers.createClass(AppCamera, [{
      key: 'initialize',
      value: function initialize() {
        babelHelpers.get(AppCamera.prototype.__proto__ || Object.getPrototypeOf(AppCamera.prototype), 'initialize', this).call(this);

        this.$turnOnBtn = $('.enable-cam');
        this.$takeSnapshotBtn = $('.capture-img');
        this.$cam = '.image-wrap';
        this.$preview = $('.preview-img');
      }
    }, {
      key: 'process',
      value: function process() {
        babelHelpers.get(AppCamera.prototype.__proto__ || Object.getPrototypeOf(AppCamera.prototype), 'process', this).call(this);

        this.handleWebcam();
        this.setupControls();
      }
    }, {
      key: 'handleWebcam',
      value: function handleWebcam() {
        var w = $(this.$preview).width();
        var h = $(this.$preview).height();

        var camOptions = {
          width: w,
          height: h,
          dest_width: 640,
          dest_height: 480,
          image_format: 'jpeg',
          jpeg_quality: 90,
          force_flash: false
        };

        Webcam.set(camOptions);
      }
    }, {
      key: 'setupControls',
      value: function setupControls() {
        var _this2 = this;

        var self = this;

        this.$turnOnBtn.on('click', function (e) {
          _this2.$preview.addClass('d-none');
          $(_this2.$cam).removeClass('d-none');
          Webcam.attach(_this2.$cam);
        });

        this.$takeSnapshotBtn.on('click', function (e) {
          Webcam.snap(function (data_uri) {
            _this2.$preview.removeClass('d-none');
            $(self.$cam).addClass('d-none');
            self.$preview.attr('src', data_uri);
            Webcam.reset();
          });
        });
      }
    }]);
    return AppCamera;
  }(_BaseApp3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppCamera();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.AppCamera = AppCamera;
  exports.run = run;
  exports.getInstance = getInstance;
  exports.default = AppCamera;
});