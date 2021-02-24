import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

//Modelo Change
(function() {
  $(document).on('change', '#modelo', function(event) {
    if ($(this).val() == 'D') {
      $('#sorter-det').removeClass('d-none')
      $('#sorter-res').addClass('d-none')
      $('#group-by').addClass('d-none')
    } else if ($(this).val() == 'R') {
      $('#sorter-det').addClass('d-none')
      $('#sorter-res').removeClass('d-none')
      $('#group-by').addClass('d-none')
    } else if ($(this).val() == 'A') {
      $('#sorter-det').removeClass('d-none')
      $('#sorter-res').addClass('d-none')
      $('#group-by').removeClass('d-none')
    } else if ($(this).val() == 'AR') {
      $('#sorter-det').removeClass('d-none')
      $('#sorter-res').addClass('d-none')
      $('#group-by').removeClass('d-none')
    }
  })
})();

// Fetch Data
window.fetchData = function(route, token, page) {
  let formData = new FormData()
  formData.append('dataini', $('#dataini').val())
  formData.append('datafim', $('#datafim').val())
  formData.append('idcliente', $('#idcliente').val().toString())
  formData.append('idguia', $('#idguia').val().toString())
  formData.append('idtiposervico', $('#idtiposervico').val().toString())
  formData.append('idmaterial', $('#idmaterial').val().toString())
  formData.append('idcor', $('#idcor').val().toString())
  formData.append('milini', $('#milini').val().toString())
  formData.append('milfim', $('#milfim').val().toString())
  formData.append('modelo', $('#modelo').val().toString())
  formData.append('sortbydet', $('#sortbydet').val())
  formData.append('sortbyres', $('#sortbyres').val())
  formData.append('grupos', $('#grupos').val())

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
      alert('erro')
      console.log(jqXHR)
    }
  })
};
