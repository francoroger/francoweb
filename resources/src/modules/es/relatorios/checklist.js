import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

(function() {
  $(document).on('click', '.select2-all', function(event) {
    event.preventDefault();
    $(this).parent().find('select > option[value!=""]').prop("selected","selected");
    $(this).parent().find('select').trigger("change");    
  });
})();

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

  let formData = new FormData()
  formData.append('dataini', $('#dataini').val())
  formData.append('datafim', $('#datafim').val())
  formData.append('idcliente', $('#idcliente').val().toString())
  formData.append('idproduto', $('#idproduto').val().toString())
  formData.append('idmaterial', $('#idmaterial').val().toString())
  formData.append('idfornec', $('#idfornec').val().toString())
  formData.append('status', $('#status').val().toString())
  formData.append('status_check', $('#status_check').val().toString())
  formData.append('sortby', $('#sortby').val().toString())
  
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
