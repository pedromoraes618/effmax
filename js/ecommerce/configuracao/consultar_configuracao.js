$(document).ready(function () {
    $("#aba_site").trigger('click')

})


$("#aba_frete").click(function () {
    var aba = "frete"
    abaConfig(aba)
})

$("#aba_site").click(function () {
    var aba = "site"
    abaConfig(aba)
})

$("#aba_politicas").click(function () {
    var aba = "politicas"
    abaConfig(aba)
})
$("#aba_layout").click(function () {
    var aba = "layout"
    abaConfig(aba)
})
$("#aba_integracao").click(function () {
    var aba = "integracao"
    abaConfig(aba)
})

function abaConfig(aba) {

    $.ajax({
        type: 'GET',
        data: "consultar_configuracao=inicial&aba=" + aba,
        url: "view/ecommerce/configuracao/aba/" + aba + ".php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .layout").html(result);
        },
    });
}