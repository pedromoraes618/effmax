//abrir a pagina de edição do formulario, pegando o id 
$(".provisionar_nf_entrada").click(function () {

    var nf_id = $(this).attr("nf_entrada_id")
    $.ajax({
        type: 'GET',
        data: "provisionamento_nf_entrada=true&tipo=faturado&nf_id=" + nf_id,
        url: "view/include/recebimento_nf/tela_provisionamento_faturado_entrada.php",
        success: function (result) {
            return $(".modal_show").html(result) + $("#modal_recebimento_nf_faturado").modal('show');
        },
    });
});


//editar venda
$(".editar_nf_entrada").click(function () {
    var nf_id = $(this).attr("nf_entrada_id")
    var codigo_nf = $(this).attr("codigo_nf")

    $.ajax({
        type: 'GET',
        data: "editar_nf_entrada=true&form_id=" + nf_id + "&codigo_nf=" + codigo_nf,
        url: "view/compra/compra_mercadoria/compra_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_compra").modal('show');

        },
    });
})

//editar venda
$(".devolucao_nf_entrada").click(function () {
    var nf_entrada_id = $(this).attr("nf_entrada_id")

    $.ajax({
        type: 'GET',
        data: "devolucao_nf=true&form_id=" + nf_entrada_id + "&tipo=entradadev",
        url: "view/include/devolucao_nf/devolucao_nf.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_devolucao_nf").modal('show')

        },
    });
})