
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var status_pedido = document.getElementById("status_pedido");
var status_pagamento = document.getElementById("status_pagamento");
var forma_pgt = document.getElementById("forma_pgt");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var produto_id = document.getElementById("produto_id");

// Código para consultar pré-venda inicial
$.ajax({
    type: 'GET',
    data: {
        consultar_pre_venda: 'inicial',
        data_inicial: data_inicial.value,
        data_final: data_final.value
    },
    url: 'view/ecommerce/pre_venda/table/consultar_pre_venda.php',
    success: function (result) {
        $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    }
});

// Código para realizar a pesquisa detalhada
$("#pesquisar_filtro_pesquisa").click(function () {

    $.ajax({
        type: 'GET',
        data: {
            consultar_pre_venda: 'detalhado',
            conteudo_pesquisa: conteudo_pesquisa.value,
            status_pedido: status_pedido.value,
            status_pagamento: status_pagamento.value,
            forma_pgt: forma_pgt.value,
            data_inicial: data_inicial.value,
            data_final: data_final.value,
            produto_id: produto_id.value
        },
        url: 'view/ecommerce/pre_venda/table/consultar_pre_venda.php',
        success: function (result) {
            $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        }
    });
});