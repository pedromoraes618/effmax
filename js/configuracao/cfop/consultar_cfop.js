
$("#adicionar_cfop").click(function () {
    
    $.ajax({
        type: 'GET',
        data: "cadastrar_cfop=true",
        url: "view/configuracao/cfop/cfop_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_cfop").modal('show');

        },
    });
})


//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_cfop=inicial",
    url: "view/configuracao/cfop/table/consultar_cfop.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});

4
$("#pesquisar_filtro_pesquisa").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_cfop=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/configuracao/cfop/table/consultar_cfop.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


