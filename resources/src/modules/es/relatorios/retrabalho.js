import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function ($) {
  Site.run();
});

//Modelo Change
(function () {
  $(document).on('change', '#modelo', function (event) {
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

  $(document).on('click', '.select2-all', function (event) {
    event.preventDefault();
    $(this).parent().find('select > option[value!=""]').prop("selected", "selected");
    $(this).parent().find('select').trigger("change");
  });

  $(document).on('submit', '#filter-form', function () {
    if ($('#modelo').val() == 'A' || $('#modelo').val() == 'AR') {
      if ($('#grupos').val() == '') {
        toastr.error("Informe pelo menos um grupo!");
        return false
      }
    }
  });
})();

// Fetch Data
window.fetchData = function (route, token, page) {
  if ($('#dataini').val() == '') {
    toastr.error("Informe a data inicial!")
    return false
  }
  if ($('#datafim').val() == '') {
    toastr.error("Informe a data final!")
    return false
  }
  if ($('#modelo').val() == 'A' || $('#modelo').val() == 'AR') {
    if ($('#grupos').val() == '') {
      toastr.error("Informe pelo menos um grupo!");
      return false
    }
  }


  let formData = new FormData()
  formData.append('dataini', $('#dataini').val())
  formData.append('datafim', $('#datafim').val())
  formData.append('idcliente', $('#idcliente').val().toString())
  formData.append('idtipofalha', $('#idtipofalha').val().toString())
  formData.append('idtiposervico', $('#idtiposervico').val().toString())
  formData.append('idmaterial', $('#idmaterial').val().toString())
  formData.append('idcor', $('#idcor').val().toString())
  formData.append('milini', $('#milini').val().toString())
  formData.append('milfim', $('#milfim').val().toString())
  formData.append('modelo', $('#modelo').val().toString())
  formData.append('sortbydet', $('#sortbydet').val())
  formData.append('sortbyres', $('#sortbyres').val())
  formData.append('grupos', $('#grupos').val())

  route += page ? "page=" + page : ''

  return $.ajax({
    url: route,
    headers: { 'X-CSRF-TOKEN': token },
    type: 'POST',
    data: formData,
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      $('#result').html(data.view)
      var el = $("#result")
      $('html,body').animate({ scrollTop: el.offset().top - 80 }, 'slow')
    },
    error: function (jqXHR, textStatus, errorThrown) {
      window.toastr.error(jqXHR.responseJSON.message)
      console.log(jqXHR)
    }
  })
};
