$(".editar_ajuste").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var data_ajuste = $(this).attr("data_ajuste")
    var numero_ajuste = $(this).attr("numero_ajuste")
    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "ajuste_estoque=true&codigo_nf=" + codigo_nf + "&data_ajuste=" + data_ajuste+"&numero_ajuste="+numero_ajuste,
        url: "view/estoque/ajuste_estoque/ajuste_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_ajuste_estoque").modal('show');;

        },
    });
})

