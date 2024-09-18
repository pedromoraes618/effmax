$(".editar_fabricante").click(function(e) {
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(500)
    let id_fabricante= $(this).attr("id_fabricante")
   
    $.ajax({    
        type: 'GET',
        data: "editar_fabricante=true&id_fabricante=" + id_fabricante,
        url: "view/estoque/fabricante/editar_fabricante.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;
})