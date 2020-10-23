import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();

  $('.image-wrap').magnificPopup({
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

var enableAutosave = function() {
  changed = true;
    $('#btn-autosave').prop('disabled', false);
    $('#btn-autosave .ladda-label').html('<i class="icon wb-check mr-10" aria-hidden="true"></i> Salvar');
    $('#btn-autosave').addClass('btn-success');
    $('#btn-autosave').removeClass('btn-default');
}

window.autosave = function() {
  if (changed) {
    $('#btn-autosave').trigger('click');
    changed = false;
  }
  setTimeout(autosave, updatetime)
};

//Form change
(function() {
  $(document).on('change', 'form :input', enableAutosave)
})();

//Ajax Save btn click
(function() {
  $(document).on('click', '#btn-autosave', function() {
    //Button
    var btn = $(this);
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
    formData.append('id', $('input[name="id"]').val());
    var itens = [];
    $('input[name*="[id]"]').each(function(i) {
      var name = $(this).attr('name');
      itens.push({
        id: $(this).val(),
        status_check: $('input[name="'+name.replace('id', 'status_check')+'"]:checked').val(),
        obs_check: $('input[name="'+name.replace('id', 'obs_check')+'"]').val()
      })
    });
    formData.append('itens', JSON.stringify(itens));

    //Ajax
    $.ajax({
      url: route,
      headers: {'X-CSRF-TOKEN': token},
      type: 'POST',
      data: formData,
      contentType: false,
      cache: false,
      processData:false,
      success: function (data)
      {
        l.stop();
        btn.html('<i class="icon wb-check mr-10" aria-hidden="true"></i> Salvo');
        $('#btn-autosave').prop('disabled', true);
        $('#btn-autosave').addClass('btn-default');
        $('#btn-autosave').removeClass('btn-success');
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        console.log(jqXHR);
      }
    });
  });
})();

// Filter
(function() {
  $(document).on('click', '.dropdown-item', function() {
    let btn = $(this).parent().parent();

    btn.find('.selected-item').html($(this).text());

    $(this).parent()
      .find('.dropdown-item.active')
      .each(function () {
        $(this).attr('aria-expanded', false)
          .removeClass('active')
      });

    $(this).addClass('active').attr('aria-expanded', true);

    let filters = [];
    $('.dropdown-item.active').each(function(i,v) {
      let filter = $(v).attr('data-filter');
      if (filter !== '*') {
        filters.push('.'+filter );
      }
    });
    let selector = filters.join('');

    grid = $('#itens_catalogo').isotope({
      filter: selector
    });

  });
})();

// Toggle border on check
(function() {
  $('input[type=radio]').on('change', function() {
    $(this).parent().parent().removeClass('bg-green-100');
    $(this).parent().parent().removeClass('bg-red-100');
    $(this).parent().parent().removeClass('bg-blue-100');
    $(this).parent().parent().parent().removeClass('Status_Verificado');
    $(this).parent().parent().parent().removeClass('Status_NaoVerificado');
    $(this).parent().parent().parent().removeClass('Status_Aprovado');
    $(this).parent().parent().parent().removeClass('Status_Reprovado');
    $(this).parent().parent().parent().removeClass('Status_Externo');

    if (this.value == 'S') {
      $(this).parent().parent().addClass('bg-green-100');
      $(this).parent().parent().parent().addClass('Status_Verificado');
      $(this).parent().parent().parent().addClass('Status_Aprovado');
    }
    else if (this.value == 'N') {
      $(this).parent().parent().addClass('bg-red-100');
      $(this).parent().parent().parent().addClass('Status_Verificado');
      $(this).parent().parent().parent().addClass('Status_Reprovado');
    }
    else if (this.value == 'E') {
      $(this).parent().parent().addClass('bg-blue-100');
      $(this).parent().parent().parent().addClass('Status_NaoVerificado');
      $(this).parent().parent().parent().addClass('Status_Externo');
    } 
    else {
      $(this).parent().parent().parent().addClass('Status_NaoVerificado');
    }
  });
})();

// Uncheck
(function() {
  $('input[type=radio]').on('click', function() {
    let s_color = $(this).parent().parent().hasClass('bg-green-100');
    let n_color = $(this).parent().parent().hasClass('bg-red-100');
    let e_color = $(this).parent().parent().hasClass('bg-blue-100');
    let el_name = $(this).attr('name');
    let elem = $('input[name="'+el_name+'"]')[3];
    
    if (this.value == 'S' && s_color) {
      $(this).parent().parent().removeClass('bg-green-100');
      $(this).parent().parent().parent().removeClass('Status_Verificado');
      $(this).parent().parent().parent().removeClass('Status_Aprovado');
      $(this).parent().parent().parent().addClass('Status_NaoVerificado');
      $(this).removeAttr('checked');
      $(elem).attr('checked', '');
      enableAutosave();
    } else if (this.value == 'N' && n_color) {
      $(this).parent().parent().removeClass('bg-red-100');
      $(this).parent().parent().parent().removeClass('Status_Verificado');
      $(this).parent().parent().parent().removeClass('Status_Reprovado');
      $(this).parent().parent().parent().addClass('Status_NaoVerificado');
      $(this).removeAttr('checked');
      $(elem).attr('checked', '');
      enableAutosave();
    } else if (this.value == 'E' && e_color) {
      $(this).parent().parent().removeClass('bg-blue-100');
      $(this).parent().parent().parent().removeClass('Status_Externo');
      $(this).removeAttr('checked');
      $(elem).attr('checked', '');
      enableAutosave();
    } 

    

  });
})();

// Alert on submit
(function() {
  $("#check-form").submit(function(event) {
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
      closeOnConfirm: true,
    },
    function(isConfirm) {
      if (isConfirm) {
        $('#status').val('C');
      } else {
        $('#status').val('P');
      }
      $("#check-form").unbind('submit').submit();
    });
  });
})();
