
var conteudo_pesquisa = document.getElementById("conteudo_pesquisa")
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");

$.ajax({
    type: 'GET',
    data: "consultar_ajst=inicial&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
    url: "view/estoque/ajuste_estoque/table/consultar_ajuste_estoque.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_ajst=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
        url: "view/estoque/ajuste_estoque/table/consultar_ajuste_estoque.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})



$("#adicionar_ajuste").click(function () {
    /*abrir modal */

    $.ajax({
        type: 'GET',
        data: "ajuste_estoque=true",
        url: "view/estoque/ajuste_estoque/ajuste_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_ajuste_estoque").modal('show');;

        },
    });
})
