$.ajax({
    type: 'GET',
    data: "consultar_data_funcionamento=true",
    url: "view/delivery/configuracao/modulo/data_funcionamento.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .data_funcionamento").html(result);
    },
});
$.ajax({
    type: 'GET',
    data: "consultar_frete=true",
    url: "view/delivery/configuracao/modulo/frete.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .frete").html(result);
    },
});


$.ajax({
    type: 'GET',
    data: "consultar_parametros=true",
    url: "view/delivery/configuracao/modulo/card_parametros.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .parametros").html(result);
    },
});

$.ajax({
    type: 'GET',
    data: "gerenciar_baner=true",
    url: "view/delivery/configuracao/modulo/card_baner.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .img_card_baner").html(result);
    },
});
// $.ajax({
//     type: 'GET',
//     data: "consultar_img_card_combo_tela_principal=true",
//     url: "view/delivery/configuracao/modulo/card_img_combo.php",
//     success: function(result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .img_card_combo_tela_principal").html(result);
//     },
// });