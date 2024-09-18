//abrir a pagina de edição do formulario, pegando o id 
$(".consultar_historico_credito_parceiro").click(function () {

    var parceiro_id = $(this).attr("id")

    $.ajax({
        type: 'GET',
        data: "consultar_historico_credito=true&parceiro_id=" + parceiro_id,
        url: "view/include/parceiro/historico_credito.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_historico_credito_parceiro").modal('show');

        },
    });

})

