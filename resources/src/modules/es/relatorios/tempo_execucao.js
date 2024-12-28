import $ from "jquery";
import * as Site from "Site";

$(document).ready(function($) {
    Site.run();
});

// Fetch Data
window.fetchData = function(route, token, page) {
    let formData = new FormData();
    formData.append("dataini", $("#dataini").val());
    formData.append("datafim", $("#datafim").val());
    formData.append(
        "idcliente",
        $("#idcliente")
            .val()
            .toString()
    );
    formData.append("tipo_processo", $("#tipo_processo").val());
    formData.append(
        "etapaini",
        $("#etapas")
            .val()
            .split(";")[0]
    );
    formData.append(
        "etapafim",
        $("#etapas")
            .val()
            .split(";")[1]
    );

    route += page ? "page=" + page : "";

    return $.ajax({
        url: route,
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
            $("#result").html(data.view);
            var el = $("#result");
            $("html,body").animate({ scrollTop: el.offset().top - 80 }, "slow");
        },
        error: function(jqXHR, textStatus, errorThrown) {
            window.toastr.error(jqXHR.responseJSON.message);
            console.log(jqXHR);
        }
    });
};
