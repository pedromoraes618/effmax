
var data_inicial = document.getElementById('data_inicial')
var data_final = document.getElementById('data_final')
var palavra_chave = document.getElementById('palavra_chave')

faturamento_cliente(data_inicial.value, data_final.value, "faturamento_cliente", palavra_chave.value);

$("#faturamento_cliente").click(function () {
    acao = $(this).attr("id")
    faturamento_cliente(data_inicial.value, data_final.value, acao, palavra_chave.value);
})

$("#faturamento_produto").click(function () {
    acao = $(this).attr("id")
    faturamento_produto(data_inicial.value, data_final.value, acao, palavra_chave.value);
})

$("#faturamento_vendedor").click(function () {
    acao = $(this).attr("id")
    faturamento_vendedor(data_inicial.value, data_final.value, acao, palavra_chave.value);
})

$("#faturamento_grupo_produto").click(function () {
    acao = $(this).attr("id")
    faturamento_grupo_produto(data_inicial.value, data_final.value, acao, palavra_chave.value);
})
$("#faturamento_pagamento").click(function () {
    acao = $(this).attr("id")
    faturamento_pagamento(data_inicial.value, data_final.value, acao, palavra_chave.value);
})
$("#faturamento_diario").click(function () {
    acao = $(this).attr("id")
    faturamento_diario(data_inicial.value, data_final.value, acao, palavra_chave.value);
})


function faturamento_cliente(data_inicial, data_final, acao, palavra_chave) {

    $.ajax({
        type: 'GET',
        data: "faturamento=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&palavra_chave=" + palavra_chave,
        url: "view/faturamento/relatorio_faturamento/table/faturamento_cliente.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}
function faturamento_produto(data_inicial, data_final, acao, palavra_chave) {

    $.ajax({
        type: 'GET',
        data: "faturamento=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&palavra_chave=" + palavra_chave,
        url: "view/faturamento/relatorio_faturamento/table/faturamento_produto.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}
function faturamento_grupo_produto(data_inicial, data_final, acao, palavra_chave) {

    $.ajax({
        type: 'GET',
        data: "faturamento=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&palavra_chave=" + palavra_chave,
        url: "view/faturamento/relatorio_faturamento/table/faturamento_grupo_produto.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}

function faturamento_vendedor(data_inicial, data_final, acao, palavra_chave) {

    $.ajax({
        type: 'GET',
        data: "faturamento=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&palavra_chave=" + palavra_chave,
        url: "view/faturamento/relatorio_faturamento/table/faturamento_vendedor.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}
function faturamento_pagamento(data_inicial, data_final, acao, palavra_chave) {

    $.ajax({
        type: 'GET',
        data: "faturamento=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&palavra_chave=" + palavra_chave,
        url: "view/faturamento/relatorio_faturamento/table/faturamento_pagamento.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}
function faturamento_diario(data_inicial, data_final, acao, palavra_chave) {

    $.ajax({
        type: 'GET',
        data: "faturamento=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&palavra_chave=" + palavra_chave,
        url: "view/faturamento/relatorio_faturamento/table/faturamento_diario.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}

// function print_ralatorio(data_inicial, data_final, conta_financeira) {
//     var janela = "view/relatorio/modelo/modelo_1.php?relatorio=resumo_extrato_financeiro&data_inicial=" + data_inicial + "&data_final=" + data_final + "&conta_financeira=" + conta_financeira
//     window.open(janela, 'popuppage',
//         'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
// }