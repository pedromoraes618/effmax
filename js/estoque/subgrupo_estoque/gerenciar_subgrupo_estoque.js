$(document).ready(function() {
    $.ajax({
        type: 'GET',
        data: "cadastro_subgrupo=true",
        url: "view/estoque/subgrupo_estoque/cadastro_subgrupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    //consultar parametros
    $.ajax({
        type: 'GET',
        data: "consultar_subgrupo=inicial",
        url: "view/estoque/subgrupo_estoque/table/consultar_subgrupo_estoque.php", //alerta
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})

//valores do campo de pesquisa
let conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
//consultar usuario especifico
$("#pesquisar_filtro_pesquisa").click(function(e) {
    $.ajax({
        type: 'GET',
        data: "consultar_subgrupo=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/estoque/subgrupo_estoque/table/consultar_subgrupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})


