//abrir a pagina de edição do formulario, pegando o id 
$(".diagnostico").click(function () {

    var ordem_id = $(this).attr("id")
    $.ajax({
        type: 'GET',
        data: "diagnostico_tecnico=true&acao=editar&form_id=" + ordem_id,
        url: "view/servico/diagnostico_tecnico/diagnostico_tecnico_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_diagnostico_tecnico").modal('show');

        },
    });

})


