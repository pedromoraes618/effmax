$(".editar_subgrupo").click(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    let id_subgrupo = $(this).attr("id_subgrupo")

    $.ajax({
        type: 'GET',
        data: "editar_subgrupo_estoque=true&id_subgrupo=" + id_subgrupo,
        url: "view/estoque/subgrupo_estoque/editar_subgrupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });

    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;
})