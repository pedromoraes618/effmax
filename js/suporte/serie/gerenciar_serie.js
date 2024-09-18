$(document).ready(function() {
    $.ajax({
        type: 'GET',
        data: "cadastro_serie=true",
        url: "view/suporte/serie/cadastro_serie.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    //consultar informação tabela
    $.ajax({
        type: 'GET',
        data: "consultar_serie=inicial",
        url: "view/suporte/serie/table/consultar_serie.php",
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
        data: "consultar_serie=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/suporte/serie/table/consultar_serie.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})