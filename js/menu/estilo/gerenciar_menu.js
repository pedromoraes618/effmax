
// Aguarde o evento load
$(window).on('load', function() {
    // Selecione a div pelo ID ou classe
    estilo()
  });
function estilo() {
    $.ajax({
        type: "POST",
        data: "background=true",
        url: "modal/menu/estilo/gerenciar_menu.php",
        async: false
    }).then(sucesso, falha);
    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
       //   $(".bloco .bloco-left").css('background-color',$dados.valores['background_nav'])
    //       $(".bloco .bloco-left").attr("style", "background-color:#9fa6bc");
        } else {
            
        }
    }

    function falha() {
        console.log("erro");
    }
}

