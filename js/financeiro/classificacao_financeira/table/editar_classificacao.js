//abrir a pagina de edição do formulario, pegando o id 
$(".editar_classificacao").click(function () {

    var classificacao_id = $(this).attr("classificacao_id")

    $.ajax({
        type: 'GET',
        data: "forma_pagamento=true&acao=editar&form_id=" + classificacao_id,
        url: "view/financeiro/classificacao_financeira/classificacao_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_classificacao_financeira").modal('show');

        },
    });

})


