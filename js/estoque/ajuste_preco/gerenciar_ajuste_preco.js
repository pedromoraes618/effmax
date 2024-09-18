
//consultar informação tabela
$(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block")
$(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de cadastro
$.ajax({
    type: 'GET',
    data: "consultar_ajuste_preco=inicial",
    url: "view/estoque/ajuste_preco/consultar_ajuste_preco.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
    },
});



$(document).ready(function () {
    // Inicializar o Chosen no select com a classe 'chosen-select'
    $('.chosen-select').chosen({
        width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

    });
});