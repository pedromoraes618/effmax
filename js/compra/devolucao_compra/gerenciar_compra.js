

//consultar informação tabela
$(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block")
$(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de cadastro

$.ajax({
    type: 'GET',
    data: "consultar_devolucao_compra=inicial",
    url: "view/compra/devolucao_compra/consultar_compra.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
    },
});



