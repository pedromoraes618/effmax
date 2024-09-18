



var data_inicial = document.getElementById('data_inicial')
var data_final = document.getElementById('data_final')
// var conta_financeira = document.getElementById('conta_financeira')
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");


//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_resumo_periodo=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
    url: "view/financeiro/resumo_cobranca/table/consultar_resumo_periodo.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-2 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_resumo_periodo=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/financeiro/resumo_cobranca/table/consultar_resumo_periodo.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2 .tabela").html(result);
        },
    });
})


