$(document).ready(function() {
    //tela para incluir
    $.ajax({
        type: 'GET',
        data: "verificar_tarefa=true&usuario_logado="+user_logado.value,
        url: "view/lembrete/minha_tarefa/verificar_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    //consultar tabela
    
    $.ajax({
        type: 'GET',
        data: "consultar_tarefa=inicial&usuario_logado="+user_logado.value,
        url: "view/lembrete/minha_tarefa/table/consultar_minha_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})

//valores do campo de pesquisa
let conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
let data_inicial = document.getElementById("data_inicial")
let data_final = document.getElementById("data_final")
//consultar //tabela detalhado
$("#pesquisar_tarefa").click(function() {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "consultar_tarefa=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value +
            "&data_inicial="+data_inicial.value+"&data_final="+data_final.value + "&usuario_logado="+user_logado,
        url: "view/lembrete/minha_tarefa/table/consultar_minha_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})  