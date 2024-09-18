

//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var tipo_dt = document.getElementById("tipo_dt");
//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_compra=inicial",
    url: "view/compra/compra_mercadoria/table/consultar_compra.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {

    $.ajax({
        type: 'GET',
        data: "consultar_compra=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value +
            "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value + "&tipo_dt=" + tipo_dt.value,
        url: "view/compra/compra_mercadoria/table/consultar_compra.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


$("#adicionar_compra").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicicionar_nf_entrada=true",
        url: "view/compra/compra_mercadoria/compra_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_compra").modal('show');

        },
    });
})
$("#importar_xml").click(function () {

    $.ajax({
        type: 'GET',
        data: "modal_importar_xml_nf_entrada=true",
        url: "view/include/importar_xml/importar_xml_nf_entrada.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_importar_xml_nf_entrada").modal('show');

        },
    });
})





$("#select_dt_emissao").click(function () {
    $('.input-group-text').html("Dt Emissão")
    $('#tipo_dt').val("dt_emissao")
})

$("#select_dt_entrada").click(function () {
    $('.input-group-text').html("Dt Entrada")
    $('#tipo_dt').val("dt_entrada")
})