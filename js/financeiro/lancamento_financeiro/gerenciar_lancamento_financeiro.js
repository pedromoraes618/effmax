$(document).ready(function() {
    // 
    //consultar informação tabela
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de cadastro
    $.ajax({
        type: 'GET',
        data: "consultar_lancamento_financeiro=inicial",
        url: "view/financeiro/lancamento_financeiro/consultar_lancamento_financeiro.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})



