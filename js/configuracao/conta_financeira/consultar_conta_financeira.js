$("#adicionar_conta_financeira").click(function (e) {
    $.ajax({
        type: 'GET',
        data: "cadastrar_conta_fin=true",
        url: "view/configuracao/conta_financeira/conta_financeira_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_conta_financeira").modal('show');
            
        },
    });
})


//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");

if (localStorage.getItem("storage_pesquisa")) {
    var memoria_pesquisa = localStorage.getItem("storage_pesquisa");
    conteudo_pesquisa.value = memoria_pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_conta_financeira=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/configuracao/conta_financeira/table/consultar_conta_financeira.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
} else {
    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_conta_financeira=inicial",
        url: "view/configuracao/conta_financeira/table/consultar_conta_financeira.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
}


$("#pesquisar_filtro_pesquisa").click(function () {

    localStorage.setItem("storage_pesquisa", conteudo_pesquisa.value);
    $.ajax({
        type: 'GET',
        data: "consultar_conta_financeira=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/configuracao/conta_financeira/table/consultar_conta_financeira.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


