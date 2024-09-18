$(".opcao").click(function () {
    var opcao_id = $(this).data("opcao-id")
    $.ajax({
        type: 'GET',
        data: "opcao_produto=true&opcao_id=" + opcao_id + "&codigo_nf=" + codigo_nf.value,
        url: "view/include/produto/opcao_produto.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_opcao_produto").modal('show');

        },
    });
})

//ao clicar no botão cadastrar produto
$(".editar_produto").click(function () {
    var form_id = $(this).data("id")
    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "tela_produto=true&form_id=" + form_id,
        url: "view/estoque/produto_ecommerce/produto_tela.php",
        success: function (result) {
            return $(".modal_show").html(result) + $("#modal_produto").modal('show')
        },
    });
})




// $(".variante").click(function () {

//     // $.ajax({
//     //     type: 'GET',
//     //     data: "consultar_variacao=true&codigo_nf=" + codigo_nf,
//     //     url: "view/include/produto/table/consultar_variacao.php",
//     //     success: function (result) {
//     //         return $(".box-tabela-variantes").html(result)
//     //     },
//     // });

//     $.ajax({
//         type: 'GET',
//         data: "consultar_variacao=true&codigo_nf=" + codigo_nf.value,
//         url: "view/include/produto/variante_produto.php",
//         success: function (result) {
//             return $(".modal_externo").html(result) + $("#modal_variante_produto").modal('show');

//         },
//     });
// })

// function remover_marcador(marcador_id) {
//     $.ajax({
//         type: "POST",
//         data: "formulario_produto_ecommerce=true&acao=remove_marcador&id=" + marcador_id,
//         url: "modal/estoque/produto/gerenciar_produto.php",
//         async: false
//     }).then(sucesso, falha);
//     function sucesso(data) {
//         $dados = $.parseJSON(data)["dados"];
//         if ($dados.sucesso == true) {
//             consultar_marcadores(codigo_nf.value)
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Verifique!',
//                 text: $dados.title,
//                 timer: 7500,
//             })
//         }
//     }
//     function falha() {
//         console.log("erro");
//     }

// }