$.ajax({
    type: 'GET',
    data: "consultar_relatorio_delivery=inicial",
    url: "view/delivery/relatorio/modulo/vendas.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .vendas").html(result);
    },
});
$.ajax({
    type: 'GET',
    data: "consultar_relatorio_delivery=inicial",
    url: "view/delivery/relatorio/modulo/produtos_vendas.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .produtos_vendas").html(result);
    },
});
$("#pesquisar_filtro_pesquisa").click(function () {
    var data_inicial = $("#data_inicial").val()
    var data_final = $("#data_final").val()
    $.ajax({
        type: 'GET',
        data: "consultar_relatorio_delivery=detalhado&data_inicial=" + data_inicial + "&data_final=" + data_final,
        url: "view/delivery/relatorio/modulo/vendas.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .vendas").html(result);
        },
    });
    $.ajax({
        type: 'GET',
        data: "consultar_relatorio_delivery=detalhado&data_inicial=" + data_inicial + "&data_final=" + data_final,
        url: "view/delivery/relatorio/modulo/produtos_vendas.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .produtos_vendas").html(result);
        },
    });
})
// $.ajax({
//     type: 'GET',
//     data: "consultar_frete=true",
//     url: "view/delivery/configuracao/modulo/frete.php",
//     success: function(result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .frete").html(result);
//     },
// });


// $.ajax({
//     type: 'GET',
//     data: "consultar_parametros=true",
//     url: "view/delivery/configuracao/modulo/card_parametros.php",
//     success: function(result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .parametros").html(result);
//     },
// });

// $.ajax({
//     type: 'GET',
//     data: "consultar_img_card_combo_tela_principal=true",
//     url: "view/delivery/configuracao/modulo/card_img_combo.php",
//     success: function(result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .img_card_combo_tela_principal").html(result);
//     },
// });