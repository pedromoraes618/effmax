$(document).ready(function(e) {

    $.ajax({
        type: 'GET',
        data: "cadastrar=",
        url: "view/configuracao/users/cadastrar_user.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    $.ajax({
        type: 'GET',
        data: "consultar_user=",
        url: "view/configuracao/users/consultar_user.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
        },
    });
})