(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/clientes/edit', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery, global.Site);
    global.clientesEdit = mod.exports;
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();
  });

  //Force tipopessoa change
  (function () {
    (0, _jquery2.default)(document).ready(function () {
      (0, _jquery2.default)('input[name="tipopessoa"]').trigger('change');
    });
  })();

  //Change Tipo Pessoa
  (function () {
    (0, _jquery2.default)(document).on('change', 'input[name="tipopessoa"]:checked', function () {
      if ((0, _jquery2.default)(this).val() == 'F') {
        (0, _jquery2.default)('#cpf').removeAttr('readonly');
        (0, _jquery2.default)('#inscest').removeAttr('readonly');
        (0, _jquery2.default)('#cpf').mask('000.000.000-00', { reverse: true });
        (0, _jquery2.default)('label[for=cpf]').text('CPF');
        (0, _jquery2.default)('label[for=inscest]').text('RG');
        (0, _jquery2.default)('label[for=nome]').html('Apelido <span class="text-danger">*</span>');
        (0, _jquery2.default)('label[for=rzsc]').text('Nome');
      } else if ((0, _jquery2.default)(this).val() == 'J') {
        (0, _jquery2.default)('#cpf').removeAttr('readonly');
        (0, _jquery2.default)('#inscest').removeAttr('readonly');
        (0, _jquery2.default)('#cpf').mask('00.000.000/0000-00', { reverse: true });
        (0, _jquery2.default)('label[for=cpf]').text('CNPJ');
        (0, _jquery2.default)('label[for=inscest]').text('Inscrição Estadual');
        (0, _jquery2.default)('label[for=nome]').html('Nome Fantasia <span class="text-danger">*</span>');
        (0, _jquery2.default)('label[for=rzsc]').text('Razão Social');
      } else {
        (0, _jquery2.default)('#cpf').attr('readonly', 'readonly');
        (0, _jquery2.default)('#inscest').attr('readonly', 'readonly');
        (0, _jquery2.default)('label[for=cpf]').text('CNPJ / CPF');
        (0, _jquery2.default)('label[for=inscest]').text('Inscrição Estadual / RG');
        (0, _jquery2.default)('label[for=nome]').html('Apelido / Nome Fantasia <span class="text-danger">*</span>');
        (0, _jquery2.default)('label[for=rzsc]').text('Nome / Razão Social');
      }
    });
  })();

  //Busca CEP
  (function () {
    (0, _jquery2.default)(document).on('keyup', '#cep, #cep_entrega', function (event) {
      var cep = (0, _jquery2.default)(this).val().trim();
      if (cep.length == 9) {
        var endereco = void 0;
        var bairro = void 0;
        var cidade = void 0;
        var estado = void 0;
        var numero = void 0;

        if ((0, _jquery2.default)(this).attr('id') == 'cep') {
          endereco = (0, _jquery2.default)('#endereco');
          bairro = (0, _jquery2.default)('#bairro');
          cidade = (0, _jquery2.default)('#cidade');
          estado = (0, _jquery2.default)('#uf');
          numero = (0, _jquery2.default)('#numero');
        } else if ((0, _jquery2.default)(this).attr('id') == 'cep_entrega') {
          endereco = (0, _jquery2.default)('#endereco_entrega');
          bairro = (0, _jquery2.default)('#bairro_entrega');
          cidade = (0, _jquery2.default)('#cidade_entrega');
          estado = (0, _jquery2.default)('#uf_entrega');
          numero = (0, _jquery2.default)('#numero_entrega');
        }

        _jquery2.default.ajax({
          url: 'http://cep.republicavirtual.com.br/web_cep.php?cep=' + cep + '&formato=json',
          type: 'GET',
          dataType: "json",
          success: function success(data) {
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
});