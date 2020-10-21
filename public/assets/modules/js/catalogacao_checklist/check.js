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

      grid = (0, _jquery2.default)('#itens_catalogo').isotope({
        filter: selector
      });
    });
  })();

  // Toggle border on check
  (function () {
    (0, _jquery2.default)('input[type=radio]').on('change', function () {
      (0, _jquery2.default)(this).parent().parent().removeClass('bg-green-100');
      (0, _jquery2.default)(this).parent().parent().removeClass('bg-red-100');
      (0, _jquery2.default)(this).parent().parent().removeClass('bg-blue-100');
      (0, _jquery2.default)(this).parent().parent().parent().removeClass('Status_Verificado');
      (0, _jquery2.default)(this).parent().parent().parent().removeClass('Status_NaoVerificado');
      (0, _jquery2.default)(this).parent().parent().parent().removeClass('Status_Aprovado');
      (0, _jquery2.default)(this).parent().parent().parent().removeClass('Status_Reprovado');
      (0, _jquery2.default)(this).parent().parent().parent().removeClass('Status_Externo');

      if (this.value == 'S') {
        (0, _jquery2.default)(this).parent().parent().addClass('bg-green-100');
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_Verificado');
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_Aprovado');
      } else if (this.value == 'N') {
        (0, _jquery2.default)(this).parent().parent().addClass('bg-red-100');
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_Verificado');
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_Reprovado');
      } else if (this.value == 'E') {
        (0, _jquery2.default)(this).parent().parent().addClass('bg-blue-100');
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_NaoVerificado');
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_Externo');
      } else {
        (0, _jquery2.default)(this).parent().parent().parent().addClass('Status_NaoVerificado');
      }
    });
  })();

  // Toggle border on check
  (function () {
    (0, _jquery2.default)('input[type=radio]').on('click', function () {
      var s_color = (0, _jquery2.default)(this).parent().parent().hasClass('bg-green-100');
      var n_color = (0, _jquery2.default)(this).parent().parent().hasClass('bg-red-100');
      var e_color = (0, _jquery2.default)(this).parent().parent().hasClass('bg-blue-100');
      var el_name = (0, _jquery2.default)(this).attr('name');

      if (this.value == 'S' && s_color) {
        this.checked = false;
        (0, _jquery2.default)('input[name="' + el_name + '"]').val('');
        (0, _jquery2.default)(this).trigger('change');
      } else if (this.value == 'N' && n_color) {
        this.checked = false;
        (0, _jquery2.default)('input[name="' + el_name + '"]').val('');
        (0, _jquery2.default)(this).trigger('change');
      } else if (this.value == 'E' && e_color) {
        this.checked = false;
        (0, _jquery2.default)('input[name="' + el_name + '"]').val('');
        (0, _jquery2.default)(this).trigger('change');
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