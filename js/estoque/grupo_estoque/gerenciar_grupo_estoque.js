$(document).ready(function() {
    $.ajax({
        type: 'GET',
        data: "cadastro_grupo=true",
        url: "view/estoque/grupo_estoque/cadastro_grupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    //consultar informação tabela
    $.ajax({
        type: 'GET',
        data: "consultar_grupo=inicial",
        url: "view/estoque/grupo_estoque/table/consultar_grupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})

//valores do campo de pesquisa
let conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
$("#pesquisar_filtro_pesquisa").click(function(e) {
    $.ajax({
        type: 'GET',
        data: "consultar_grupo=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/estoque/grupo_estoque/table/consultar_grupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})