$(document).ready(function () {
    //consultar informação tabela
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de cadastro
    $.ajax({
        type: 'GET',
        data: "gerenciar_pedido=inicial",
        url: "view/compra/pedido_compra/consultar_pedido.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})



