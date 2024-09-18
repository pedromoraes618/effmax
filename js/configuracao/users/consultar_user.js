$(document).ready(function(e) {
    //consultar inicial
    $.ajax({
        type: 'GET',
        data: "consultar_user=inicial",
        url: "view/configuracao/users/table/consultar_user.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });
})
//valores do campo de pesquisa
let conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
let conteudo_situacao = document.getElementById("situacao_user")
//consultar usuario especifico
$("#pesquisar_user").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "consultar_user=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value+"&situacao_user="+conteudo_situacao.value,
        url: "view/configuracao/users/table/consultar_user.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });
})