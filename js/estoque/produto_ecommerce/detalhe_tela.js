
var pesquisa_conteudo_pergunta = document.getElementById("pesquisa_conteudo_pergunta")
var data_inicial = document.getElementById("data_inicial")
var data_final = document.getElementById("data_final")
var produto_id = document.getElementById("produto_id")

$("#pesquisar_filtro_pesquisa_pergunta").click(function () {
    $.ajax({
        type: 'GET',
        data: {
            consultar_pergunta_produto_ecommerce: 'detalhado',
            data_inicial: data_inicial.value,
            data_final: data_final.value,
            conteudo_pesquisa: pesquisa_conteudo_pergunta.value,
            produto_id: produto_id.value,
        },
        url: 'view/estoque/produto_ecommerce/table/consultar_pergunta.php',
        success: function (result) {
            $(".bloco-pesquisa-menu .bloco-pesquisa-1 .table_perguntas").html(result);
        },
        error: function (xhr, status, error) {
            console.error('Erro na requisição AJAX:', error);
        }
    });

})

$(document).ready(function () {
    $("#pesquisar_filtro_pesquisa_pergunta").trigger('click')
})