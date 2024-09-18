//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {

    var venda_id = $(this).attr("id")

    $.ajax({
        type: 'GET',
        data: "pre_venda=true&acao=editar&form_id=" + venda_id,
        url: "view/ecommerce/pre_venda/pre_venda_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_pre_venda").modal('show');

        },
    });

})


