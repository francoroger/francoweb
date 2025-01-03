(function (global, factory) {
    if (typeof define === "function" && define.amd) {
        define('/relatorios/tempo_execucao', ['jquery', 'Site'], factory);
    } else if (typeof exports !== "undefined") {
        factory(require('jquery'), require('Site'));
    } else {
        var mod = {
            exports: {}
        };
        factory(global.jQuery, global.Site);
        global.relatoriosTempo_execucao = mod.exports;
    }
})(this, function (_jquery, _Site) {
    'use strict';

    var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

    (0, _jquery2.default)(document).ready(function ($$$1) {
        (0, _Site.run)();
    });

    // Fetch Data
    window.fetchData = function (route, token, page) {
        var formData = new FormData();
        formData.append("dataini", (0, _jquery2.default)("#dataini").val());
        formData.append("datafim", (0, _jquery2.default)("#datafim").val());
        formData.append("idcliente", (0, _jquery2.default)("#idcliente").val().toString());
        formData.append("tipo_processo", (0, _jquery2.default)("#tipo_processo").val());
        formData.append("etapaini", (0, _jquery2.default)("#etapas").val().split(";")[0]);
        formData.append("etapafim", (0, _jquery2.default)("#etapas").val().split(";")[1]);

        route += page ? "page=" + page : "";

        return _jquery2.default.ajax({
            url: route,
            headers: { "X-CSRF-TOKEN": token },
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function success(data) {
                (0, _jquery2.default)("#result").html(data.view);
                var el = (0, _jquery2.default)("#result");
                (0, _jquery2.default)("html,body").animate({ scrollTop: el.offset().top - 80 }, "slow");
            },
            error: function error(jqXHR, textStatus, errorThrown) {
                window.toastr.error(jqXHR.responseJSON.message);
                console.log(jqXHR);
            }
        });
    };
});