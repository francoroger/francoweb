import $ from 'jquery';
import * as Site from 'Site';

(function ($) {
  $.fn.serializeObject = function () {

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

    $.each($(this).serializeArray(), function () {

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

      json = $.extend(true, json, merge);
    });

    return json;
  };
})(jQuery);

$(document).on('click', '.btn-add-item-retrabalho', function () {
  let rowbase = $('.item-retrabalho').last();
  let container = rowbase.parent();
  let clone = rowbase.clone();
  let index = parseInt(clone.data('index'));
  let cloneindex = index + 1;

  clone.attr('data-index', cloneindex);
  clone.find('input, select').each(function (i, elem) {
    $(elem).attr('name', $(elem).attr('name').replace(index, cloneindex));
    $(elem).val('');
  });

  rowbase.find('.item-retrabalho-controls').removeClass('d-none').addClass('d-flex');
  rowbase.find('.btn-add-item-retrabalho').addClass('d-none');

  container.append(clone);
});

$(document).on('click', '.btn-remove-item-retrabalho', function (e) {
  e.preventDefault();
  let row = $(this).parent().parent().parent();
  row.remove();

});

$(document).on('change', 'select[name*="idmaterial"]', function () {
  var id = $(this).val();
  var cbCores = $(this).parent().parent().find('select[name*="idcor"]');

  if (id == '') {
    cbCores.empty();
    cbCores.append(`<option></option>`);
  } else {
    $.ajax({
      url: coresUrl + id,
      dataType: "json",
      success: function (data) {
        cbCores.empty();
        cbCores.append(`<option></option>`);

        for (var k in data) {
          cbCores.append(`<option value="${data[k].id}">${data[k].descricao}</option>`);
        }
      }
    });
  }
});

$(document).on('click', '#btn-registrar', function () {

  let data = $('#retrabalho-form').serializeObject();

  $.ajax({
    url: storeRetrabalhoUrl,
    type: 'POST',
    data: data,
    headers: {
      'X-CSRF-TOKEN': apitoken
    },
    success: function (data) {
      refreshColumn('T');
      $('#retrabalho-modal').modal('hide');
    }
  });

});

$(document).on('submit', '#retrabalho-form', function (e) {
  e.preventDefault();
});
