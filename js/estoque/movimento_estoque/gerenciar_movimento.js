$(document).ready(function () {
    $.ajax({
        type: 'GET',
        data: "cunsultar_movimento_estoque=inicial",
        url: "view/estoque/movimento_estoque/consultar_movimento.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

$(document).ready(function () {
    // Inicializar o Chosen no select com a classe 'chosen-select'
    $('.chosen-select').chosen({
        width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

    });
});