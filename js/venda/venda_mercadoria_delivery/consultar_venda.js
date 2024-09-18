
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var status_recebimento = document.getElementById("status_recebimento");
var forma_pagamento = document.getElementById("forma_pgt");

$(document).ready(function () {
    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_venda=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/venda/venda_mercadoria_delivery/table/consultar_venda.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})


//adicionar venda
$("#adicionar_venda").click(function () {
    atualizar_pedido_topo()//função para mostrar que tem pedido delivery, ficarava visivel no top
    $.ajax({
        type: 'GET',
        data: "adicionar_venda=true",
        url: "view/venda/venda_mercadoria_delivery/venda_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result)+ $("#modal_adicionar_venda").modal('show');;

        },
    });
   

})





$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa
    atualizar_pedido_topo()
    $.ajax({
        type: 'GET',
        data: "consultar_venda=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value +
         "&data_final=" + data_final.value + "&status_recebimento=" + status_recebimento.value + "&forma_pgt=" + forma_pagamento.value ,
        url: "view/venda/venda_mercadoria_delivery/table/consultar_venda.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


