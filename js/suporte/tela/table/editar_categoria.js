$(".editar_categoria").click(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    let id_categoria = $(this).attr("id_categoria")
    $.ajax({
        type: 'GET',
        data: "editar_categoria=true&id_categoria=" + id_categoria,
        url: "view/suporte/tela/editar_categoria.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .bloco-cadastro-1").html(result)
            
        },
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;

})