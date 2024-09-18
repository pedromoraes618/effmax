$(".editar_tarefa").click(function(e) {
    let id_tarefa = $(this).attr("id_tarefa")

    $.ajax({
        type: 'GET',
        data: "editar_tarefa=true&id_tarefa=" + id_tarefa,
        url: "view/lembrete/tarefa/editar_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;
})