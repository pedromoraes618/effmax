$(document).ready(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", "none")
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').slideDown(100)
   
    $.ajax({
        type: 'GET',
        data: "filtro_log=",
        url: "view/configuracao/log/filtro_log.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    $.ajax({
        type: 'GET',
        data: "consultar_log=inical",
        url: "view/configuracao/log/table/consultar_log.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
        },
    });
})