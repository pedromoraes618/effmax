//abrir a pagina de edição do formulario, pegando o id 
$(".card-atendimento").click(function () {

    var atendimento_id = $(this).attr("atendimento_id")

    $.ajax({
        type: 'GET',
        data: "atendimento_tela=true&acao=editar&form_id=" + atendimento_id,
        url: "view/empresa/atendimento/atendimento_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_atendimento").modal('show');

        },
    });

})



//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {

    var atendimento_id = $(this).attr("atendimento_id")

    $.ajax({
        type: 'GET',
        data: "atendimento_tela=true&acao=editar&form_id=" + atendimento_id,
        url: "view/empresa/atendimento/atendimento_tela.php",
        success: function (result) {
            return $(".modal_externa_2").html(result) + $("#modal_atendimento").modal('show');

        },
    });

})


