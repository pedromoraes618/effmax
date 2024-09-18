//ao clicar no botão cadastrar produto
$(".detalhes").click(function(e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","none")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","block")
  //  $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de cadastro
 var id_produto = $(this).attr("id_produto")
  $.ajax({
        type: 'GET',
        data: "editar_produto=true&id_produto="+id_produto,
        url: "view/estoque/consultar_produto/visualizar_produto.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
        },
    });
})


$(".consultar_kardex").click(function(e) {
    var id_produto = $(this).attr("id_produto")
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","none")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","block")
$.ajax({
    type: 'GET',
    data: "kardex_produto=true&consulta_produto=true&id_produto="+id_produto,
    url: "view/estoque/karkex/consultar_kardex.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
    },
});
})