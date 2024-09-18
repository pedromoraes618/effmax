


//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo_contato_parceiro");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var parceiro_id = document.getElementById("parceiro_id");
var codigo_nf = document.getElementById("codigo_nf");

$("#adicionar_atendimento").click(function () {
    $.ajax({
        type: 'GET',
        data: "atendimento_tela=true&parceiro_id=" + parceiro_id.value + "&codigo_nf=" + codigo_nf.value,
        url: "view/empresa/atendimento/atendimento_tela.php",
        success: function (result) {
            return $(".modal_externa_2").html(result) + $("#modal_atendimento").modal('show');

        },
    });
})

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_contato_os=inicial&data_inicial=" + data_inicial.value + "&data_final=" +
        data_final.value + "&parceiro_id=" + parceiro_id.value + "&codigo_nf=" + codigo_nf.value,
    url: "view/include/contato/table/consultar_atendimento.php",
    success: function (result) {
        return $(".tabela_externa_modal").html(result);
    },
});


$("#pesquisar_filtro_pesquisa_contato_parceiro").click(function () {
 
    $.ajax({
        type: 'GET',
        data: "consultar_contato_os=detalhado&conteudo_pesquisa=" +
            conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" +
            data_final.value + "&parceiro_id=" + parceiro_id + "&codigo_nf=" + codigo_nf.value,
        url: "view/include/contato/table/consultar_atendimento.php",
        success: function (result) {
            return $(".tabela_externa_modal").html(result);
        },
    });

})


