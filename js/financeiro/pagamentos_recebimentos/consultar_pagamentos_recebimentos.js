
var data_inicial = (document.getElementById('data_inicial'))
var data_final = document.getElementById('data_final')
var conta_financeira = document.getElementById('conta_financeira')
var forma_pagamento = document.getElementById('forma_pagamento')
var rz_parceiro = document.getElementById('rz_parceiro')//razão social do cliente / fornecedor
var ordem = document.getElementById('ordem')//razão social do cliente / fornecedor

pagamento_recebimentos(data_inicial.value, data_final.value, "", conta_financeira.value, forma_pagamento.value, rz_parceiro.value, ordem.value);

$("#consultar_a_receber").click(function () {
    acao = $(this).attr("id")
    pagamento_recebimentos(data_inicial.value, data_final.value, acao, conta_financeira.value, forma_pagamento.value, rz_parceiro.value, ordem.value);
})

$("#consultar_a_pagar").click(function () {
    acao = $(this).attr("id")
    pagamento_recebimentos(data_inicial.value, data_final.value, acao, conta_financeira.value, forma_pagamento.value, rz_parceiro.value, ordem.value);
})

$("#consultar_recebidas").click(function () {
    acao = $(this).attr("id")
    pagamento_recebimentos(data_inicial.value, data_final.value, acao, conta_financeira.value, forma_pagamento.value, rz_parceiro.value, ordem.value);
})

$("#consultar_pagar").click(function () {
    acao = $(this).attr("id")
    pagamento_recebimentos(data_inicial.value, data_final.value, acao, conta_financeira.value, forma_pagamento.value, rz_parceiro.value, ordem.value);
})


function pagamento_recebimentos(data_inicial, data_final, acao, conta_financeira, forma_pagamento, rz_parceiro, ordem) {

    $.ajax({
        type: 'GET',
        data: "pagamentos_recebimentos=true&acao=" + acao + "&data_inicial=" + data_inicial + "&data_final=" + data_final
            + "&conta_financeira=" + conta_financeira + "&forma_pagamento=" + forma_pagamento + "&rz_paceiro=" + rz_parceiro + "&ordem=" + ordem,
        url: "view/financeiro/pagamentos_recebimentos/table/resumo.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}


// function print_ralatorio(data_inicial, data_final, conta_financeira) {
//     var janela = "view/relatorio/modelo/modelo_1.php?relatorio=resumo_extrato_financeiro&data_inicial=" + data_inicial + "&data_final=" + data_final + "&conta_financeira=" + conta_financeira
//     window.open(janela, 'popuppage',
//         'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
// }