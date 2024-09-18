$(document).ready(function () {
    $("#aba_link").trigger('click')

})


$("#aba_link").click(function () {
    var aba = "link"
    abaFerramenta(aba)
})


function abaFerramenta(aba) {

    $.ajax({
        type: 'GET',
        data: "consultar_ferramentas=inicial&aba=" + aba,
        url: "view/ecommerce/ferramentas/aba/" + aba + ".php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .layout").html(result);
        },
    });
}