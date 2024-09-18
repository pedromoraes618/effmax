//abrir a pagina de edição do formulario, pegando o id 
$(".receber_nf").click(function () {

    var nf_id = $(this).attr("nf_saida_id")
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
    } else {

        $.ajax({
            type: 'GET',
            data: "recebimento_nf=true&tipo=" + tipo_pagamento + "&nf_id=" + nf_id,
            url: "view/include/recebimento_nf/tela_recebimento_faturado.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_recebimento_nf_faturado").modal('show');
            },
        });

    }

});

$(".recibo_venda").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var serie_nf = $(this).attr("serie_nf")
    var janela = "view/venda/venda_mercadoria/recibo/recibo_nf.php?recibo=true&codigo_nf=" + codigo_nf + "&serie_nf=" + serie_nf;
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');

})
$(".recibo_quitacao").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var serie_nf = $(this).attr("serie_nf")
    var janela = "view/venda/venda_mercadoria/recibo/recibo_quitacao.php?recibo_quitacao=true&acao=venda&codigo_nf=" + codigo_nf + "&serie_nf=" + serie_nf;
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
})
$(".carne_venda").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var serie_nf = $(this).attr("serie_nf")
    var janela = "view/venda/venda_mercadoria/recibo/carne_nf.php?carne_venda=true&acao=venda&codigo_nf=" + codigo_nf + "&serie_nf=" + serie_nf;
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
})


$(".fiscal").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var nf_saida_id = $(this).attr("nf_saida_id")

    $.ajax({
        type: 'GET',
        data: "fiscal=true&form_id=" + nf_saida_id + "&&tipo=editar&codigo_nf=" + codigo_nf,
        url: "view/venda/nf_saida/fiscal_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_fiscal").modal('show')

        },
    })
})

//editar venda
$(".editar_venda_mercadoria").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var nf_saida_id = $(this).attr("nf_saida_id")
    var serie_fiscal = $(this).attr("serie_fiscal")
    if (serie_fiscal == "SIM") {
        $.ajax({
            type: 'GET',
            data: "editar_venda=true&form_id=" + nf_saida_id + "&tipo=editar&codigo_nf=" + codigo_nf,
            url: "view/venda/nf_saida/nf_tela.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_nf_fiscal").modal('show')

            },
        });
    } else {
        $.ajax({
            type: 'GET',
            data: "editar_venda=true&form_id=" + nf_saida_id + "&tipo=editar&codigo_nf=" + codigo_nf,
            url: "view/venda/venda_mercadoria/venda_tela.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_adicionar_venda").modal('show')

            },
        });
    }

})

$(".alterar_venda").click(function () {
    var codigo_nf = $(this).attr("codigo_nf")
    var nf_saida_id = $(this).attr("nf_saida_id")
    $.ajax({
        type: 'GET',
        data: "editar_venda=true&form_id=" + nf_saida_id + "&tipo=editar&codigo_nf=" + codigo_nf,
        url: "view/venda/venda_mercadoria/venda_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_adicionar_venda").modal('show')

        },
    });

})

//editar venda
$(".devolucao_nf_saida").click(function () {
    var nf_saida_id = $(this).attr("nf_saida_id")

    $.ajax({
        type: 'GET',
        data: "devolucao_nf=true&form_id=" + nf_saida_id + "&tipo=saidadev",
        url: "view/include/devolucao_nf/devolucao_nf.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_devolucao_nf").modal('show')

        },
    });
})
//editar venda
$(".estorno_nf_saida").click(function () {
    var nf_saida_id = $(this).attr("nf_saida_id")
    $.ajax({
        type: 'GET',
        data: "estorno_nf=true&form_id=" + nf_saida_id + "&tipo=saidaestorno",
        url: "view/include/devolucao_nf/devolucao_nf.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_devolucao_nf").modal('show')

        },
    });
})

//editar venda
$(".cancelar_nf_saida").click(function () {
    var id_formulario = $(this).attr("nf_saida_id")
    var codigo_nf = $(this).attr("codigo_nf")
    $.ajax({
        type: 'GET',
        data: "autorizar_acao=true&acao=cancelar_nf&mensagem=Favor, selecione o autorizador para completar a ação",
        url: "view/include/autorizacao/autorizar_acao.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result)
                + $("#modal_autorizar_acao").modal('show')
                + $("#autorizar_acao").addClass("autorizar_cancelar_nf")
                + $('#autorizar_acao').attr('id_formulario', id_formulario)
                + $('#autorizar_acao').attr('codigo_nf', codigo_nf);

        },
    })
})


//modal para consultar o parceiro
$(".dados_parceiro").click(function () {
    var parceiro_id = $(this).attr("parceiro_id")

    $.ajax({
        type: 'GET',
        data: "editar_cliente=true&parceiro_id=" + parceiro_id,
        url: "view/include/cadastrar_parceiro/editar_parceiro.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_tela_editar_parceiro").modal('show');

        },
    });
});


