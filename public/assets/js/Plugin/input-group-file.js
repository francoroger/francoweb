(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Plugin/input-group-file", [], factory);
  } else if (typeof exports !== "undefined") {
    factory();
  } else {
    var mod = {
      exports: {}
    };
    factory();
    global.PluginInputGroupFile = mod.exports;
  }
})(this, function () {
  "use strict";
});