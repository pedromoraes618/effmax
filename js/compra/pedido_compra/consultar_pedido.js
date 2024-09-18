
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var status_pedido = document.getElementById("status_pedido");

$(document).ready(function () {
    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_pedido=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/compra/pedido_compra/table/consultar_pedido.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela.tabela-consulta").html(result);
        },
    });
})


$("#adicionar_pedido").click(function () {

    $.ajax({
        type: 'GET',
        data: "pedido_tela=true",
        url: "view/compra/pedido_compra/pedido_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_pedido_compra").modal('show');;

        },
    });
})


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa

    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_pedido=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&status_pedido=" + status_pedido.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/compra/pedido_compra/table/consultar_pedido.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela.tabela-consulta").html(result);
        },
    });

})


$(document).ready(function () {
    $('.select2').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('body') // Ou o contêiner específico onde está o select
    });
});