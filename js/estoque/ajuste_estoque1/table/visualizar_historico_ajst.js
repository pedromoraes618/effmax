
$(".editar").click(function () {

    var codigo_nf = $(this).data("codigo-nf")
    var codigo = $(this).data("codigo")

    $.ajax({
        type: 'GET',
        data: "ajuste_historico=true&acao=visualizar&codigo_nf=" + codigo_nf + "&codigo=" + codigo,
        url: "view/estoque/ajuste_estoque1/historico_ajuste.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_historico_ajuste").modal('show');

        },
    });

})


