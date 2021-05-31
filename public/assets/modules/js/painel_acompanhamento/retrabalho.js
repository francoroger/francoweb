(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/painel_acompanhamento/retrabalho', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.painel_acompanhamentoRetrabalho = mod.exports;
  }
})(this, function (_jquery) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (function ($$$1) {
    $$$1.fn.serializeObject = function () {

      var self = this,
          json = {},
          push_counters = {},
          patterns = {
        "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
        "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
        "push": /^$/,
        "fixed": /^\d+$/,
        "named": /^[a-zA-Z0-9_]+$/
      };

      this.build = function (base, key, value) {
        base[key] = value;
        return base;
      };

      this.push_counter = function (key) {
        if (push_counters[key] === undefined) {
          push_counters[key] = 0;
        }
        return push_counters[key]++;
      };

      $$$1.each($$$1(this).serializeArray(), function () {

        // Skip invalid keys
        if (!patterns.validate.test(this.name)) {
          return;
        }

        var k,
            keys = this.name.match(patterns.key),
            merge = this.value,
            reverse_key = this.name;

        while ((k = keys.pop()) !== undefined) {

          // Adjust reverse_key
          reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

          // Push
          if (k.match(patterns.push)) {
            merge = self.build([], self.push_counter(reverse_key), merge);
          }

          // Fixed
          else if (k.match(patterns.fixed)) {
              merge = self.build([], k, merge);
            }

            // Named
            else if (k.match(patterns.named)) {
                merge = self.build({}, k, merge);
              }
        }

        json = $$$1.extend(true, json, merge);
      });

      return json;
    };
  })(jQuery);

  (0, _jquery2.default)(document).on('click', '.btn-add-item-retrabalho', function () {
    var rowbase = (0, _jquery2.default)('.item-retrabalho').last();
    var container = rowbase.parent();
    var clone = rowbase.clone();
    var index = parseInt(clone.data('index'));
    var cloneindex = index + 1;

    clone.attr('data-index', cloneindex);
    clone.find('input, select').each(function (i, elem) {
      (0, _jquery2.default)(elem).attr('name', (0, _jquery2.default)(elem).attr('name').replace(index, cloneindex));
      (0, _jquery2.default)(elem).val('');
    });

    rowbase.find('.item-retrabalho-controls').removeClass('d-none').addClass('d-flex');
    rowbase.find('.btn-add-item-retrabalho').addClass('d-none');

    container.append(clone);
  });

  (0, _jquery2.default)(document).on('click', '.btn-remove-item-retrabalho', function (e) {
    e.preventDefault();
    var row = (0, _jquery2.default)(this).parent().parent().parent();
    row.remove();
  });

  (0, _jquery2.default)(document).on('change', 'select[name*="idmaterial"]', function () {
    var id = (0, _jquery2.default)(this).val();
    var cbCores = (0, _jquery2.default)(this).parent().parent().find('select[name*="idcor"]');

    if (id == '') {
      cbCores.empty();
      cbCores.append('<option></option>');
    } else {
      _jquery2.default.ajax({
        url: coresUrl + id,
        dataType: "json",
        success: function success(data) {
          cbCores.empty();
          cbCores.append('<option></option>');

          for (var k in data) {
            cbCores.append('<option value="' + data[k].id + '">' + data[k].descricao + '</option>');
          }
        }
      });
    }
  });

  (0, _jquery2.default)(document).on('click', '#btn-registrar', function () {

    var data = (0, _jquery2.default)('#retrabalho-form').serializeObject();

    _jquery2.default.ajax({
      url: storeRetrabalhoUrl,
      type: 'POST',
      data: data,
      headers: {
        'X-CSRF-TOKEN': apitoken
      },
      success: function success(data) {
        refreshColumn('T');
        (0, _jquery2.default)('#retrabalho-modal').modal('hide');
      }
    });
  });

  (0, _jquery2.default)(document).on('submit', '#retrabalho-form', function (e) {
    e.preventDefault();
  });
});