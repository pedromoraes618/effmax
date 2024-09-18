//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {

    var servico_id = $(this).attr("id")

    $.ajax({
        type: 'GET',
        data: "servico=true&acao=editar&form_id=" + servico_id,
        url: "view/servico/lista_servico/servico_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_servico").modal('show');

        },
    });

})


