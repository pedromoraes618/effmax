$(document).ready(function() {
    $.ajax({
        type: 'GET',
        data: "cadastro_parametro=true",
        url: "view/suporte/parametro/cadastro_parametro.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_parametro=inicial",
        url: "view/suporte/parametro/table/consultar_parametro.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})

//valores do campo de pesquisa
let conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
let conteudo_configuracao = document.getElementById("configuracao")
//consultar 
$("#pesquisar_parametro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "consultar_parametro=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value +
            "&conteudo_configuracao=" + conteudo_configuracao.value,
        url: "view/suporte/parametro/table/consultar_parametro.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})