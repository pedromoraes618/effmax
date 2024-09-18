

var data_inicial = (document.getElementById('data_inicial'))
var data_final = document.getElementById('data_final')
var pesquisa_conteudo_historico = document.getElementById('pesquisa_conteudo_historico')
var parceiro_id = document.getElementById('id')

historico_parceiro("financeiro.php", parceiro_id.value, data_inicial.value, data_final.value, "financeiro", pesquisa_conteudo_historico.value);

$("#financeiro").click(function () {
    acao = $(this).attr("id")
    arquivo = "financeiro.php"
    historico_parceiro(arquivo, parceiro_id.value, data_inicial.value, data_final.value, acao, pesquisa_conteudo_historico.value);
})

$("#duplicatas_atraso").click(function () {
    acao = $(this).attr("id")
    arquivo = "duplicatas_atraso.php"
    historico_parceiro(arquivo, parceiro_id.value, data_inicial.value, data_final.value, acao, pesquisa_conteudo_historico.value);
})


$("#vendas").click(function () {
    acao = $(this).attr("id")
    arquivo = "vendas.php"
    historico_parceiro(arquivo, parceiro_id.value, data_inicial.value, data_final.value, acao, pesquisa_conteudo_historico.value);
})

$("#produtos_vendidos").click(function () {
    acao = $(this).attr("id")
    arquivo = "produtos_vendidos.php"
    historico_parceiro(arquivo, parceiro_id.value, data_inicial.value, data_final.value, acao, pesquisa_conteudo_historico.value);
})

$("#compras").click(function () {
    acao = $(this).attr("id")
    arquivo = "compras.php"
    historico_parceiro(arquivo, parceiro_id.value, data_inicial.value, data_final.value, acao, pesquisa_conteudo_historico.value);
})

$("#produtos_comprados").click(function () {
    acao = $(this).attr("id")
    arquivo = "produtos_comprados.php"
    historico_parceiro(arquivo, parceiro_id.value, data_inicial.value, data_final.value, acao, pesquisa_conteudo_historico.value);
})

function historico_parceiro(arquivo, parceiro_id, data_inicial, data_final, acao, palavra_chave) {
    $.ajax({
        type: 'GET',
        data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
        url: "view/empresa/historico/table/" + arquivo,
        success: function (result) {
            return $('.tabela_externa').html(result);
        },
    });
}


// function historico_parceiro_financeiro(parceiro_id, data_inicial, data_final, acao, palavra_chave) {
//     $.ajax({
//         type: 'GET',
//         data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
//         url: "view/empresa/historico/table/financeiro.php",
//         success: function (result) {
//             return $('.tabela_externa').html(result);
//         },
//     });
// }

// function historico_parceiro_f(parceiro_id, data_inicial, data_final, acao, palavra_chave) {
//     $.ajax({
//         type: 'GET',
//         data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
//         url: "view/empresa/historico/table/financeiro.php",
//         success: function (result) {
//             return $('.tabela_externa').html(result);
//         },
//     });
// }
// function historico_parceiro_venda(parceiro_id, data_inicial, data_final, acao, palavra_chave) {
//     $.ajax({
//         type: 'GET',
//         data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
//         url: "view/empresa/historico/table/vendas.php",
//         success: function (result) {
//             return $('.tabela_externa').html(result);
//         },
//     });
// }

// function historico_parceiro_prd_vendidos(parceiro_id, data_inicial, data_final, acao, palavra_chave) {
//     $.ajax({
//         type: 'GET',
//         data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
//         url: "view/empresa/historico/table/produtos_vendidos.php",
//         success: function (result) {
//             return $('.tabela_externa').html(result);
//         },
//     });
// }

// function historico_parceiro_compras(parceiro_id, data_inicial, data_final, acao, palavra_chave) {
//     $.ajax({
//         type: 'GET',
//         data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
//         url: "view/empresa/historico/table/compras.php",
//         success: function (result) {
//             return $('.tabela_externa').html(result);
//         },
//     });
// }

// function historico_parceiro_prd_compras(parceiro_id, data_inicial, data_final, acao, palavra_chave) {
//     $.ajax({
//         type: 'GET',
//         data: "filtro_historico_parceiro=true&acao=" + acao + "&parceiro_id=" + parceiro_id + "&data_inicial=" + data_inicial + "&data_final=" + data_final + "&palavra_chave=" + palavra_chave,
//         url: "view/empresa/historico/table/produtos_comprados.php",
//         success: function (result) {
//             return $('.tabela_externa').html(result);
//         },
//     });
// }
