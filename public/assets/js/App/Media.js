(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/App/Media", [], factory);
  } else if (typeof exports !== "undefined") {
    factory();
  } else {
    var mod = {
      exports: {}
    };
    factory();
    global.AppMedia = mod.exports;
  }
})(this, function () {
  "use strict";
});