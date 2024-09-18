
//valores do campo de pesquisa //pesquisa via filtro
var periodo = $(".periodo").val()

// $(document).ready(function () {
//     var dados = { 'periodo': periodo };
//     consultar_container(dados, 'resumo_pedidos_1');
// });


$(".periodo").click(function () {
    var periodo = $(this).val()
    var dados = { 'periodo': periodo };
    consultar_container(dados, 'resumo_pedidos_1');
})

$(".periodo").trigger('click')
function consultar_container(dados, container) {
    $.ajax({
        type: 'GET',
        data: "consultar_container_dashboard=" + container + "&" + $.param(dados),
        url: "view/ecommerce/dashboard/containers/" + container + ".php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 ." + container).html(result);
        },
    });
}
