$("#voltar_consulta").click(function(e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block") // remover tela de cadastro
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de consulta
        $.ajax({
        type: 'GET',
        data: "consultar_produto=inicial",
        url: "view/estoque/consultar_produto/consultar_produto.php",
        success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    
})
    


