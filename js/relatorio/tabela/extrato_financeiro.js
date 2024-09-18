
var relatorio = document.getElementById("relatorio")

if (relatorio.value == "resumo_extrato_financeiro") {
    relatorio_extrato_financeiro()
}

function relatorio_extrato_financeiro() {
    $.ajax({
        type: 'GET',
        data: "extrato_financeiro=true",
        url: "../../../view/relatorio/tabela/extrato_financeiro.php",
        success: function (result) {
            return $('.container').html(result);
        },
    });
}