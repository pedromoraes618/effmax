$(".responder").click(function () {
    var pergunta_id = $(this).attr('id')

    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "pergunta_tela=true&form_id=" + pergunta_id,
        url: "view/include/produto/pergunta_cliente_ecommerce.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_externo").html(result) + $("#modal_pergunta").modal('show');;

        },
    });

})