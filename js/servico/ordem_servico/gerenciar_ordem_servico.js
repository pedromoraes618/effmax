
//consultar informação tabela
$(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block")
$(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de cadastro
$.ajax({
    type: 'GET',
    data: "consultar_ordem_servico=inicial",
    url: "view/servico/ordem_servico/consultar_ordem_servico.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
    },
});



