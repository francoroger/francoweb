import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

// Fetch Data
window.fetchData = function(route, token, page) {
  if($('#dataini').val() == '') {
    toastr.error("Informe a data inicial!")
    return false
  }
  if($('#datafim').val() == '') {
    toastr.error("Informe a data final!")
    return false
  }
  if($('#idtanque').val() == '') {
    toastr.error("Selecione o tanque!")
    return false
  }

  let formData = new FormData()
  formData.append('dataini', $('#dataini').val())
  formData.append('datafim', $('#datafim').val())
  formData.append('idtanque', $('#idtanque').val().toString())

  route += page ? "page="+page : ''

  return $.ajax({
    url: route,
    headers: {'X-CSRF-TOKEN': token},
    type: 'POST',
    data: formData,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data) {
      $('#result').html(data.view)
      var el = $("#result")
      $('html,body').animate({scrollTop: el.offset().top - 80},'slow')
    },
    error: function(jqXHR, textStatus, errorThrown)
    {
      window.toastr.error(jqXHR.responseJSON.message)
      console.log(jqXHR)
    }
  })
};
