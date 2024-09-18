



var data_inicial = document.getElementById('data_inicial')
var data_final = document.getElementById('data_final')
var conta_financeira = document.getElementById('conta_financeira')

extrato_financeiro(data_inicial.value, data_final.value, conta_financeira.value);
$("#consultar").addClass('active').siblings().removeClass('active');

$("#consultar").click(function () {
    $(this).addClass('active').siblings().removeClass('active');
    extrato_financeiro(data_inicial.value, data_final.value, conta_financeira.value);
})

$("#resumo").click(function () {
    $(this).addClass('active').siblings().removeClass('active');
    resumo_extrato_financeiro(data_inicial.value, data_final.value, conta_financeira.value);
})

$("#print_relatorio").click(function () {
    print_ralatorio(data_inicial.value, data_final.value, conta_financeira.value)
})

function extrato_financeiro(data_inicial, data_final, conta_financeira) {

    $.ajax({
        type: 'GET',
        data: "extrato_financeiro=true&acao=extrato&data_inicial=" + data_inicial
            + "&data_final=" + data_final + "&conta_financeira=" + conta_financeira,
        url: "view/financeiro/extrato_financeiro/table/extrato.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}
function resumo_extrato_financeiro(data_inicial, data_final, conta_financeira) {

    $.ajax({
        type: 'GET',
        data: "extrato_financeiro=true&acao=resumo_extrato&data_inicial=" + data_inicial
            + "&data_final=" + data_final + "&conta_financeira=" + conta_financeira,
        url: "view/financeiro/extrato_financeiro/table/resumo.php",
        success: function (result) {
            return $('.tabela').html(result);
        },
    });
}

function print_ralatorio(data_inicial, data_final, conta_financeira) {
    var janela = "view/relatorio/modelo/modelo_1.php?relatorio=resumo_extrato_financeiro&data_inicial=" + data_inicial + "&data_final=" + data_final + "&conta_financeira=" + conta_financeira
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
}