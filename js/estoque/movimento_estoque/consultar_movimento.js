
var data_inicial = document.getElementById('data_inicial')
var data_final = document.getElementById('data_final')
var palavra_chave = document.getElementById('palavra_chave')

movimento_estoque(data_inicial.value, data_final.value, "movimento_estoque", palavra_chave.value, []);
$("#movimento_estoque").addClass('active').siblings().removeClass('active');

$("#movimento_estoque").click(function () {
    $(this).addClass('active').siblings().removeClass('active');

    var grupos_prd = [];

    // Iterar sobre os options selecionados e adicionar seus valores ao array
    $('#grupo option:selected').each(function () {
        grupos_prd.push($(this).val());
    });

    acao = $(this).attr("id");
    // Chamar a função movimento_estoque passando o array de options selecionados
    movimento_estoque(data_inicial.value, data_final.value, acao, palavra_chave.value, grupos_prd);
});

$("#estoque_minimo_maximo").click(function () {
    $(this).addClass('active').siblings().removeClass('active');

    var grupos_prd = [];

    // Iterar sobre os options selecionados e adicionar seus valores ao array
    $('#grupo option:selected').each(function () {
        grupos_prd.push($(this).val());
    });

    acao = $(this).attr("id");
    estoque_minimo_maximo(data_inicial.value, data_final.value, acao, palavra_chave.value, grupos_prd);
});

$("#sugestao_compra").click(function () {
    $(this).addClass('active').siblings().removeClass('active');

    var grupos_prd = [];

    // Iterar sobre os options selecionados e adicionar seus valores ao array
    $('#grupo option:selected').each(function () {
        grupos_prd.push($(this).val());
    });

    acao = $(this).attr("id");
    sugestao_compra(data_inicial.value, data_final.value, acao, palavra_chave.value, grupos_prd);
});

$("#estoque_zerado").click(function () {
    $(this).addClass('active').siblings().removeClass('active');

    var grupos_prd = [];

    // Iterar sobre os options selecionados e adicionar seus valores ao array
    $('#grupo option:selected').each(function () {
        grupos_prd.push($(this).val());
    });

    acao = $(this).attr("id");
    estoque_zerado(data_inicial.value, data_final.value, acao, palavra_chave.value, grupos_prd);
});

function movimento_estoque(data_inicial, data_final, acao, palavra_chave, grupos_prd) {

    $.ajax({
        type: 'GET',
        data: {
            movimento_estoque: true,
            acao: acao,
            data_inicial: data_inicial,
            data_final: data_final,
            palavra_chave: palavra_chave,
            grupos_prd: grupos_prd
        },
        url: "view/estoque/movimento_estoque/table/movimento_estoque.php",
        success: function (result) {
            $('.tabela').html(result);
        },
    });
}

function estoque_minimo_maximo(data_inicial, data_final, acao, palavra_chave, grupos_prd) {

    $.ajax({
        type: 'GET',
        data: {
            movimento_estoque: true,
            acao: acao,
            data_inicial: data_inicial,
            data_final: data_final,
            palavra_chave: palavra_chave,
            grupos_prd: grupos_prd
        },
        url: "view/estoque/movimento_estoque/table/estoque_minimo_maximo.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}


function estoque_zerado(data_inicial, data_final, acao, palavra_chave, grupos_prd) {

    $.ajax({
        type: 'GET',
        data: {
            movimento_estoque: true,
            acao: acao,
            data_inicial: data_inicial,
            data_final: data_final,
            palavra_chave: palavra_chave,
            grupos_prd: grupos_prd
        },
        url: "view/estoque/movimento_estoque/table/estoque_zerado.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}
function sugestao_compra(data_inicial, data_final, acao, palavra_chave, grupos_prd) {

    $.ajax({
        type: 'GET',
        data: {
            movimento_estoque: true,
            acao: acao,
            data_inicial: data_inicial,
            data_final: data_final,
            palavra_chave: palavra_chave,
            grupos_prd: grupos_prd
        },
        url: "view/estoque/movimento_estoque/table/sugestao_compra.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}

// $("#faturamento_produto").click(function () {
//     acao = $(this).attr("id")
//     faturamento_produto(data_inicial.value, data_final.value, acao, palavra_chave.value);
// })

// $("#faturamento_vendedor").click(function () {
//     acao = $(this).attr("id")
//     faturamento_vendedor(data_inicial.value, data_final.value, acao, palavra_chave.value);
// })

// $("#faturamento_grupo_produto").click(function () {
//     acao = $(this).attr("id")
//     faturamento_grupo_produto(data_inicial.value, data_final.value, acao, palavra_chave.value);
// })
// $("#faturamento_pagamento").click(function () {
//     acao = $(this).attr("id")
//     faturamento_pagamento(data_inicial.value, data_final.value, acao, palavra_chave.value);
// })
// $("#faturamento_diario").click(function () {
//     acao = $(this).attr("id")
//     faturamento_diario(data_inicial.value, data_final.value, acao, palavra_chave.value);
// })

