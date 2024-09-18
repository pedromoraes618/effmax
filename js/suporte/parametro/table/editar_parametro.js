$(".editar_parametro").click(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    let id_parametro = $(this).attr("id_parametro")

    $.ajax({
        type: 'GET',
        data: "editar_parametro=true&id_parametro=" + id_parametro,
        url: "view/suporte/parametro/editar_parametro.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result)
        },
       
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição
})