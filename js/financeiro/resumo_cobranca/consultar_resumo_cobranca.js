var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");


//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_resumo_cobranca=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value,
    url: "view/financeiro/resumo_cobranca/table/consultar_resumo_cobranca.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-2 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_resumo_cobranca=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/financeiro/resumo_cobranca/table/consultar_resumo_cobranca.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2 .tabela").html(result);
        },
    });
})

