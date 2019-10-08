(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jquery-mask', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginJqueryMask = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'mask';

  var Mask = function (_Plugin) {
    babelHelpers.inherits(Mask, _Plugin);

    function Mask() {
      babelHelpers.classCallCheck(this, Mask);
      return babelHelpers.possibleConstructorReturn(this, (Mask.__proto__ || Object.getPrototypeOf(Mask)).apply(this, arguments));
    }

    babelHelpers.createClass(Mask, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.mask) {
          return;
        }

        var $el = this.$el,
            options = this.options;

        if (this.options.type === 'cellphone') {

          var maskBehavior = function maskBehavior(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
          },
              maskOptions = {
            onKeyPress: function onKeyPress(val, e, field, options) {
              field.mask(maskBehavior.apply({}, arguments), options);
            }
          };

          $el.mask(maskBehavior, maskOptions);
        } else {
          $el.mask(options.pattern);
        }
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Mask;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Mask);

  exports.default = Mask;
});