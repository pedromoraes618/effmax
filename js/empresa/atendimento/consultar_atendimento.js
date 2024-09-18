
$("#adicionar_atendimento").click(function () {
    $.ajax({
        type: 'GET',
        data: "atendimento_tela=true",
        url: "view/empresa/atendimento/atendimento_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_atendimento").modal('show');

        },
    });
})


//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var status_atd = document.getElementById("status");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_atendimento=inicial",
    url: "view/empresa/atendimento/table/consultar_atendimento.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {

    $.ajax({
        type: 'GET',
        data: "consultar_atendimento=detalhado&conteudo_pesquisa=" +
            conteudo_pesquisa.value + "&status=" + status_atd.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/empresa/atendimento/table/consultar_atendimento.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


