(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/kanban', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginKanban = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'kanban';

  var Kanban = function (_Plugin) {
    babelHelpers.inherits(Kanban, _Plugin);

    function Kanban() {
      babelHelpers.classCallCheck(this, Kanban);
      return babelHelpers.possibleConstructorReturn(this, (Kanban.__proto__ || Object.getPrototypeOf(Kanban)).apply(this, arguments));
    }

    babelHelpers.createClass(Kanban, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        var $kb = Sortable.create(this.$el.get(0), this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          group: 'shared',
          animation: 150,
          onEnd: function onEnd(evt) {
            doChangeEvent(evt);
          }
        };
      }
    }]);
    return Kanban;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Kanban);

  exports.default = Kanban;
});