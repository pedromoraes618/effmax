//abrir a pagina de edição do formulario, pegando o id 
$(".editar_conta_financeira").click(function () {
    var conta_financeira_id = $(this).attr("conta_financeira_id")

    $.ajax({
        type: 'GET',
        data: "conta_financeira=true&acao=editar&form_id=" + conta_financeira_id,
        url: "view/configuracao/conta_financeira/conta_financeira_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_conta_financeira").modal('show');

        },
    });
    
})


