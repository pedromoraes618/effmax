
$("#adicionar_classificacao").click(function () {
 
    $.ajax({
        type: 'GET',
        data: "cadastrar_classificacao=true",
        url: "view/financeiro/classificacao_financeira/classificacao_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_classificacao_financeira").modal('show');

        },
    });
})


//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_classificacao_financeira=inicial",
    url: "view/financeiro/classificacao_financeira/table/consultar_classificacao.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {

    $.ajax({
        type: 'GET',
        data: "consultar_classificacao_financeira=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/financeiro/classificacao_financeira/table/consultar_classificacao.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


