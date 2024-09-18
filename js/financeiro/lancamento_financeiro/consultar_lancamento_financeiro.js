
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var status_lancamento_financeiro = document.getElementById("status_lancamento");
var classificao_financeiro = document.getElementById("classificao_financeiro");
var tipo_lancamento = document.getElementById("tipo_lancamento");
var forma_pagamento_consulta = document.getElementById("forma_pagamento_consulta");
var conta_finan = document.getElementById("conta_finan");


//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_lancamento_financeiro=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
    url: "view/financeiro/lancamento_financeiro/table/consultar_lancamento_financeiro.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});

//adicionar receita
$("#adicionar_lancamento_receita").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_financeiro=true&tipo=RECEITA",
        url: "view/financeiro/lancamento_financeiro/lancamento_financeiro_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_lancamento_financeiro").modal('show');

        },
    });
})

//adicionar despesa
$("#adicionar_lancamento_despesa").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_financeiro=true&tipo=DESPESA",
        url: "view/financeiro/lancamento_financeiro/lancamento_financeiro_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_lancamento_financeiro").modal('show');

        },
    });
})

//adicionar lançamentos multiplos
$("#adicionar_lancamento_multiplo").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_financeiro=true&tipo=MULTIPLO",
        url: "view/financeiro/lancamento_financeiro/lancamento_multiplo_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result)
                + $("#modal_lancamento_multiplo").modal('show');

        },
    });
})



//adicionar lançamentos de transferencia de valores entre conta
$("#adicionar_lancamento_transferencia_valores").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_transferencia_valores=true",
        url: "view/financeiro/transferencia_valores/lancamento.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result)
                + $("#modal_transferencia_valores").modal('show');

        },
    });
})

$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa

    $.ajax({
        type: 'GET',
        data: "consultar_lancamento_financeiro=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value
            + "&data_final=" + data_final.value + "&status_lancamento=" + status_lancamento_financeiro.value
            + "&classificao_financeiro=" + classificao_financeiro.value
            + "&tipo_lancamento=" + tipo_lancamento.value
            + "&forma_pagamento=" + forma_pagamento_consulta.value
            + "&conta_financeira=" + conta_finan.value,
        url: "view/financeiro/lancamento_financeiro/table/consultar_lancamento_financeiro.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


$(document).ready(function() {
    $('.select2').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('body') // Ou o contêiner específico onde está o select
    });
});