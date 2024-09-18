$(".editar_grupo_estoque").click(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    let id_grupo_estoque = $(this).attr("id_grupo")

    $.ajax({
        type: 'GET',
        data: "editar_grupo_estoque=true&id_grupo=" + id_grupo_estoque,
        url: "view/estoque/grupo_estoque/editar_grupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;
})