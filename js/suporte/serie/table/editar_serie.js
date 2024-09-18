$(".editar_serie").click(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    let id_serie = $(this).attr("id_serie")

    $.ajax({
        type: 'GET',
        data: "editar_serie=true&id_serie=" + id_serie,
        url: "view/suporte/serie/editar_serie.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;

})