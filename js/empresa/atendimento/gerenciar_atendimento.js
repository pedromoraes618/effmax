$(document).ready(function () {
    // 
    //consultar informação tabela
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none")
    $.ajax({
        type: 'GET',
        data: "consultar_atendimento=inicial",
        url: "view/empresa/atendimento/consultar_atendimento.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})



