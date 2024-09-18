//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {

    var usuario_id = $(this).attr("id")

    $.ajax({
        type: 'GET',
        data: "usuario=true&acao=editar&form_id=" + usuario_id,
        url: "view/configuracao/usuario/usuario_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_usuario").modal('show');

        },
    });

})


