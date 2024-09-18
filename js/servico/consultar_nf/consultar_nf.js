
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var status_recebimento = document.getElementById("status_recebimento");


$(document).ready(function () {
    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_documento_os=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/servico/consultar_nf/table/consultar_nf.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_documento_os=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value +
            "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value + "&status_recebimento=" + status_recebimento.value,
        url: "view/servico/consultar_nf/table/consultar_nf.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
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