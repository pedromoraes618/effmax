
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var status_pagamento = document.getElementById("status_pagamento");
var status_pedido = document.getElementById("status_pedido");
var forma_pgt = document.getElementById("forma_pgt");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");



//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_pedido=inicial&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
    url: "view/ecommerce/pedido/table/consultar_pedido.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_pedido=detalhado&conteudo_pesquisa=" +
            conteudo_pesquisa.value + "&status_pagamento=" + status_pagamento.value +
             "&status_pedido=" + status_pedido.value + "&fpg=" +
            forma_pgt.value + "&data_inicial=" + data_inicial.value + 
            "&data_final=" + data_final.value,
        url: "view/ecommerce/pedido/table/consultar_pedido.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})

