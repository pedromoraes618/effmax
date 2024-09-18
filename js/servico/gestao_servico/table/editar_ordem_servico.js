//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {

    var ordem_servico_id = $(this).attr("id")

    $.ajax({
        type: 'GET',
        data: "ordem_servico=true&acao=editar&form_id=" + ordem_servico_id,
        url: "view/servico/ordem_servico/ordem_servico_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_ordem_servico").modal('show');
        },
    });
})


//abrir a pagina de edição do formulario, pegando o id 
$(".contato").click(function () {
    var ordem_servico_id = $(this).attr("id")
    var parceiro_id = $(this).attr("parceiro_id")
    var codigo_nf = $(this).attr("codigo_nf")

    $.ajax({
        type: 'GET',
        data: "consultar_contato=true&acao=consultar&form_id=" + ordem_servico_id + "&codigo_nf=" + codigo_nf + "&parceiro_id=" + parceiro_id,
        url: "view/include/contato/contato_parceiro.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_contato_parceiro").modal('show');

        },
    });
})


