
// $(document).ready(function () {
//     $("#aba_site").trigger('click')

// })


$("#aba_ajuste_item").click(function () {
    var aba = "item"
    abaConfig(aba)
})

$("#aba_consultar_ajuste").click(function () {
    var aba = "consultar_ajuste"
    abaConfig(aba)
})

$("#aba_ajuste_lote").click(function () {
    var aba = "lote"
    abaConfig(aba)
})
$("#aba_ajuste_promocao").click(function () {
    var aba = "promocao"
    abaConfig(aba)
})

// $("#aba_ajuste_promocao").click(function () {
//     var aba = "politicas"
//     abaConfig(aba)
// })
abaConfig("consultar_ajuste")


function abaConfig(aba) {

    $.ajax({
        type: 'GET',
        data: "consultar_ajuste_preco=inicial&aba=" + aba,
        url: "view/estoque/ajuste_preco/aba/" + aba + ".php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .layout").html(result);
        },
    });
}