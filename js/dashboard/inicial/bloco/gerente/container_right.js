//adicionar venda
$("#adicionar_venda").click(function () {
    var venda_delivery = $(this).attr('venda_delivery')
    if (venda_delivery == "S") {//tela de venda para delivery
        $.ajax({
            type: 'GET',
            data: "adicionar_venda=true",
            url: "view/venda/venda_mercadoria_delivery/venda_tela.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .modal_atalho").html(result) + $("#modal_adicionar_venda").modal('show');;

            },
        });
    } else {
        $.ajax({
            type: 'GET',
            data: "adicionar_venda=true",
            url: "view/venda/venda_mercadoria/venda_tela.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .modal_atalho").html(result) + $("#modal_adicionar_venda").modal('show');;

            },
        });
    }
})

//adicionar venda
$(".adicionar_lancamento").click(function () {
    var tipo = $(this).attr('tipo')

    $.ajax({
        type: 'GET',
        data: "lancamento_rapido=true&tipo=" + tipo,
        url: "view/financeiro/lancamento_rapido/lancamento.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .modal_atalho").html(result) + $("#modal_lancamento_rapido").modal('show');;

        },
    });


})

$("#abertura_fechamento_cx").click(function () {
    $.ajax({
        type: 'GET',
        data: "abertura_fechamento_cx=true",
        url: "view/caixa/abertura_fechamento/consultar_caixa_modal.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .modal_atalho").html(result) + $("#modal_abertura_fechamento_cx").modal('show');;

        },
    });


})


$("#adicionar_atendimento").click(function () {
    $.ajax({
        type: 'GET',
        data: "atendimento_tela=true",
        url: "view/empresa/atendimento/atendimento_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .modal_atalho").html(result) + $("#modal_atendimento").modal('show');

        },
    });
})

