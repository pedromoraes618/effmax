//abrir a pagina de edição do formulario, pegando o id 
$(".receber_nf").click(function () {

    var nf_id = $(this).attr("venda_mercadoria_id")
    var tipo_pagamento = $(this).attr("tipo_pagamento")

    if (tipo_pagamento == "cartao") {
        $.ajax({
            type: 'GET',
            data: "recebimento_nf=true&tipo=" + tipo_pagamento + "&nf_id=" + nf_id,
            url: "view/include/recebimento_nf/tela_recebimento.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_recebimento_nf").modal('show');
            },
        });
    }

});

$(".recibo_venda").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var serie_nf = $(this).attr("serie_nf")
    var janela = "view/venda/venda_mercadoria_delivery/recibo/recibo_nf.php?recibo=true&codigo_nf=" + codigo_nf + "&serie_nf=" + serie_nf;
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');

})

$(".comanda").click(function () {
    var pedido_id = $(this).attr("pedido_id")

    var janela = "view/delivery/pedido/comanda/modelo_2.php?comanda2=true&pedido_id=" + pedido_id ;
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');

})

//editar venda
$(".editar_venda_mercadoria").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var id_venda = $(this).attr("venda_mercadoria_id")
    $.ajax({
        type: 'GET',
        data: "editar_venda=true&form_id=" + id_venda + "&tipo=editar&codigo_nf=" + codigo_nf,
        url: "view/venda/venda_mercadoria_delivery/venda_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_adicionar_venda").modal('show')

        },
    });
})
