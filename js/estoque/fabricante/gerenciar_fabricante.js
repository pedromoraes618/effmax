$(document).ready(function() {
    $.ajax({
        type: 'GET',
        data: "cadastro_fabricante=true",
        url: "view/estoque/fabricante/cadastro_fabricante.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    //consultar informação tabela
    $.ajax({
        type: 'GET',
        data: "consultar_fabricante=inicial",
        url: "view/estoque/fabricante/table/consultar_fabricante.php",
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
        data: "consultar_fabricante=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/estoque/fabricante/table/consultar_fabricante.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})