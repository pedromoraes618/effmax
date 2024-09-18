
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var classificao_financeiro = document.getElementById("classificao_financeiro");
var status_pagamento = document.getElementById("status_pagamento");
var pagamento = document.getElementById("pagamento");


$("#adicionar_classificacao").click(function () {

    $.ajax({
        type: 'GET',
        data: "cadastrar_classificacao=true",
        url: "view/financeiro/classificacao_financeira/classificacao_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_classificacao_financeira").modal('show');

        },
    });
})


//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_baixa_duplicata=inicial&data_inicial=" + data_inicial.value + "&data_final=" + 
    data_final.value,
    url: "view/financeiro/baixa_duplicata/table/consultar_baixa_duplicata.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_baixa_duplicata=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value
        + "&data_final=" + data_final.value + "&classificao_financeiro=" + classificao_financeiro.value + "&status_pagamento=" + status_pagamento.value+"&pagamento="+pagamento.value,
        url: "view/financeiro/baixa_duplicata/table/consultar_baixa_duplicata.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


$(document).ready(function() {
    $('.select2').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('body') // Ou o contêiner específico onde está o select
    });
});

