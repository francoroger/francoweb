(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/catalogacao_checklist/check', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.catalogacao_checklistCheck = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();

    grid = $$$1('#itens_catalogo').isotope();

    $$$1('.image-wrap').magnificPopup({
      type: 'image',
      closeOnContentClick: true,
      mainClass: 'mfp-fade',
      tClose: 'Fechar (Esc)',
      tLoading: 'Carregando...',
      gallery: {
        enabled: false
      }
    });

    autosave();
  });

  var updatetime = 30000;

  var changed = false;

  var grid;

  window.autosave = function () {
    if (changed) {
      (0, _jquery2.default)('#btn-autosave').trigger('click');
      changed = false;
    }
    setTimeout(autosave, updatetime);
  };

  //Form change
  (function () {
    (0, _jquery2.default)(document).on('change', 'form :input', function () {
      changed = true;
      (0, _jquery2.default)('#btn-autosave').prop('disabled', false);
      (0, _jquery2.default)('#btn-autosave .ladda-label').html('<i class="icon wb-check mr-10" aria-hidden="true"></i> Salvar');
      (0, _jquery2.default)('#btn-autosave').addClass('btn-success');
      (0, _jquery2.default)('#btn-autosave').removeClass('btn-default');
    });
  })();

  //Ajax Save btn click
  (function () {
    (0, _jquery2.default)(document).on('click', '#btn-autosave', function () {
      //Button
      var btn = (0, _jquery2.default)(this);
      btn.html('Salvando...');

      //Ajax Params
      var route = btn.data('url');
      var token = btn.data('token');

      //Ladda
      var l = Ladda.create(document.querySelector('#btn-autosave'));
      l.start();
      l.isLoading();
      l.setProgress(0 - 1);

      //FormData
      var formData = new FormData();
      formData.append('id', (0, _jquery2.default)('input[name="id"]').val());
      var itens = [];
      (0, _jquery2.default)('input[name*="[id]"]').each(function (i) {
        var name = (0, _jquery2.default)(this).attr('name');
        itens.push({
          id: (0, _jquery2.default)(this).val(),
          status_check: (0, _jquery2.default)('input[name="' + name.replace('id', 'status_check') + '"]:checked').val(),
          obs_check: (0, _jquery2.default)('input[name="' + name.replace('id', 'obs_check') + '"]').val()
        });
      });
      formData.append('itens', JSON.stringify(itens));

      //Ajax
      _jquery2.default.ajax({
        url: route,
        headers: { 'X-CSRF-TOKEN': token },
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function success(data) {
          l.stop();
          btn.html('<i class="icon wb-check mr-10" aria-hidden="true"></i> Salvo');
          (0, _jquery2.default)('#btn-autosave').prop('disabled', true);
          (0, _jquery2.default)('#btn-autosave').addClass('btn-default');
          (0, _jquery2.default)('#btn-autosave').removeClass('btn-success');
        },
        error: function error(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
        }
      });
    });
  })();

  // Filter
  (function () {
    (0, _jquery2.default)(document).on('click', '.dropdown-item', function () {
      var btn = (0, _jquery2.default)(this).parent().parent();

      btn.find('.selected-item').html((0, _jquery2.default)(this).text());

      (0, _jquery2.default)(this).parent().find('.dropdown-item.active').each(function () {
        (0, _jquery2.default)(this).attr('aria-expanded', false).removeClass('active');
      });

      (0, _jquery2.default)(this).addClass('active').attr('aria-expanded', true);

      var filters = [];
      (0, _jquery2.default)('.dropdown-item.active').each(function (i, v) {
        var filter = (0, _jquery2.default)(v).attr('data-filter');
        if (filter !== '*') {
          filters.push('.' + filter);
        }
      });
      var selector = filters.join('');

      grid.isotope({
        filter: selector
      });

      grid.on('arrangeComplete', function (filteredItems) {});
    });
  })();

  // Toggle border on check
  (function () {
    (0, _jquery2.default)('input[type=radio]').change(function () {
      (0, _jquery2.default)(this).parent().parent().removeClass('border-success');
      (0, _jquery2.default)(this).parent().parent().removeClass('border-danger');

      if (this.value == 'S') {
        (0, _jquery2.default)(this).parent().parent().addClass('border-success');
      } else if (this.value == 'N') {
        (0, _jquery2.default)(this).parent().parent().addClass('border-danger');
      }
    });
  })();

  // Alert on submit
  (function () {
    (0, _jquery2.default)("#check-form").submit(function (event) {
      event.preventDefault();
      swal({
        title: "Finalizar Verificação",
        text: "A catalogação está completamente verificada?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        //cancelButtonClass: "btn-danger",
        confirmButtonText: 'Sim, Finalizar!',
        cancelButtonText: 'Não',
        closeOnConfirm: true
      }, function (isConfirm) {
        if (isConfirm) {
          (0, _jquery2.default)('#status').val('C');
        } else {
          (0, _jquery2.default)('#status').val('P');
        }
        (0, _jquery2.default)("#check-form").unbind('submit').submit();
      });
    });
  })();
});