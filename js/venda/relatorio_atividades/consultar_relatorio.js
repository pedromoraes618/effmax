var data_inicial = document.getElementById('data_inicial')
var data_final = document.getElementById('data_final')



$(".consultar_relatorio").click(function () {
    var consulta = $(this).attr('id')
    var dados = {
        'data_inicial': data_inicial.value,
        'data_final': data_final.value,
        'consulta': consulta,
    };

    consultar_container(dados, consulta);
})

$("#vendas").trigger('click')

function consultar_container(dados, container) {
    $.ajax({
        type: 'GET',
        data: "consultar_container_relatorio_atividades=" + container + "&" + $.param(dados),
        url: "view/venda/relatorio_atividades/containers/" + container + ".php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .dashboard").html(result);
        },
    });
}
