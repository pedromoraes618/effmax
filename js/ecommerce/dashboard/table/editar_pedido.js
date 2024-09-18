//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {

    var pedido_id = $(this).attr("id")

    $.ajax({
        type: 'GET',
        data: "pedido=true&acao=editar&form_id=" + pedido_id,
        url: "view/ecommerce/pedido/pedido_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) 
            + $("#modal_pedido").modal('show');

        },
    });

})


