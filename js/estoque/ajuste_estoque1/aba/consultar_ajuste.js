
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
var usuario_id = document.getElementById("usuario_id")
var data_inicial = document.getElementById("data_inicial")
var data_final = document.getElementById("data_final")

//consultar tabela
$.ajax({
    type: 'GET',
    data: {
        consultar_ajuste: 'inicial',
        data_inicial: data_inicial.value,
        data_final: data_final.value
    },
    url: "view/estoque/ajuste_estoque1/table/consultar_ajuste.php",

    success: function (result) {
        $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
    error: function (xhr, status, error) {
        console.error('Erro na consulta:', status, error);
    }
});



$("#pesquisar_filtro_pesquisa").click(function () {
    $.ajax({
        type: 'GET',
        data: {
            consultar_ajuste: 'detalhado',
            conteudo_pesquisa: conteudo_pesquisa.value,
            data_inicial: data_inicial.value,
            data_final: data_final.value,
            usuario_id: usuario_id.value
        },
        url: "view/estoque/ajuste_estoque1/table/consultar_ajuste.php",
        success: function (result) {
            $(".bloco-pesquisa-menu .bloco-pesquisa-1 .layout .tabela").html(result);
        },
        error: function (xhr, status, error) {
            console.error('Erro na requisição AJAX:', error);
        }
    });

})

$(document).ready(function () {
    // Inicializar o Chosen no select com a classe 'chosen-select'
    $('.chosen-select').chosen({
        width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

    });
});