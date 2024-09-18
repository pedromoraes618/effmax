$(document).ready(function() {
 
    $.ajax({
        type: 'GET',
        data: "cadastro_tarefa=true",
        url: "view/lembrete/tarefa/cadastro_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });

    
    $.ajax({
        type: 'GET',
        data: "consultar_tarefa=inicial&user_logado="+user_logado.value,
        url: "view/lembrete/tarefa/table/consultar_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})


//valores do campo de pesquisa
let conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
let conteudo_status = document.getElementById("status")
let data_inicial = document.getElementById("data_inicial")
let data_final = document.getElementById("data_final")
//consultar //tabela detalhado
$("#pesquisar_tarefa").click(function(e) {


    $.ajax({
        type: 'GET',
        data: "consultar_tarefa=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value +
            "&conteudo_status=" + conteudo_status.value+"&data_inicial="+data_inicial.value+"&data_final="+data_final.value+"&user_logado="+user_logado,
        url: "view/lembrete/tarefa/table/consultar_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})