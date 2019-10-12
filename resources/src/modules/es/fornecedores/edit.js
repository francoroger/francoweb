import $ from 'jquery';
import * as Site from 'Site';

$(document).ready(function($) {
  Site.run();
});

//Force tipopessoa change
(function() {
  $(document).ready(function() {
    $('input[name="tipopessoa"]').trigger('change');
  });
})();

//Change Tipo Pessoa
(function() {
  $(document).on('change', 'input[name="tipopessoa"]:checked', function() {
    if ($(this).val() == 'F') {
      $('#cpf').removeAttr('readonly');
      $('#inscest').removeAttr('readonly');
      $('#cpf').mask('000.000.000-00', {reverse: true});
      $('label[for=cpf]').text('CPF');
      $('label[for=inscest]').text('RG');
      $('label[for=nome]').html('Apelido <span class="text-danger">*</span>');
      $('label[for=rzsc]').text('Nome');
    } else if ($(this).val() == 'J') {
      $('#cpf').removeAttr('readonly');
      $('#inscest').removeAttr('readonly');
      $('#cpf').mask('00.000.000/0000-00', {reverse: true});
      $('label[for=cpf]').text('CNPJ');
      $('label[for=inscest]').text('Inscrição Estadual');
      $('label[for=nome]').html('Nome Fantasia <span class="text-danger">*</span>');
      $('label[for=rzsc]').text('Razão Social');
    } else {
      $('#cpf').attr('readonly', 'readonly');
      $('#inscest').attr('readonly', 'readonly');
      $('label[for=cpf]').text('CNPJ / CPF');
      $('label[for=inscest]').text('Inscrição Estadual / RG');
      $('label[for=nome]').html('Apelido / Nome Fantasia <span class="text-danger">*</span>');
      $('label[for=rzsc]').text('Nome / Razão Social');
    }
  })
})();

//Busca CEP
(function() {
  $(document).on('keyup', '#cep', function(event) {
    let cep = $(this).val().trim();
    if (cep.length == 9) {
      let endereco = $('#endereco');
      let bairro = $('#bairro');
      let cidade = $('#cidade');
      let estado = $('#uf');
      let numero = $('#numero');

      $.ajax({
        url: 'http://cep.republicavirtual.com.br/web_cep.php?cep='+cep+'&formato=json',
        type: 'GET',
        dataType: "json",
        success: function (data)
        {
          if (data.resultado == '1') {
            endereco.val(data.tipo_logradouro + ' ' + data.logradouro);
            bairro.val(data.bairro);
            cidade.val(data.cidade);
            estado.val(data.uf);
            numero.focus();
          } else {
            endereco.val("");
            bairro.val("");
            cidade.val("");
            estado.val("");
          }
        }
      });
    }
  });
})();
