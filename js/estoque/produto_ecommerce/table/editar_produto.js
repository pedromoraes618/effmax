//ao clicar no botão cadastrar produto
$(".editar_produto").click(function () {
    var form_id = $(this).data("id")
    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "tela_produto=true&form_id=" + form_id,
        url: "view/estoque/produto_ecommerce/produto_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_produto").modal('show')
        },
    });
})


$(".consultar_kardex").click(function () {
    var form_id = $(this).data("id")
    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "kardex_produto=true&form_id=" + form_id,
        url: "view/estoque/kardex/kardex_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_kardex").modal('show')
        },
    });
})

$(".detalhe_produto").click(function () {
    var form_id = $(this).data("id")
    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "detalhe_produto=true&form_id=" + form_id,
        url: "view/estoque/produto_ecommerce/detalhe_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_detalhe").modal('show')
        },
    });
})
// //modal para adicionar observacao
// $(".modal_delivery").click(function () {
//     var form_id = $(this).attr("id_produto")
//     $.ajax({
//         type: 'GET',
//         data: "produto_delivery=true&produto_id=" + form_id,
//         url: "view/include/produto/produto_delivery.php",
//         success: function (result) {
//             return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_produto_delivery").modal('show');
//         },
//     });
// });
