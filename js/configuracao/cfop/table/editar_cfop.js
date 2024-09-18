//abrir a pagina de edição do formulario, pegando o id 
$(".editar_cfop").click(function () {

    var cfop_id = $(this).attr("cfop_id")
   
    $.ajax({
        type: 'GET',
        data: "forma_pagamento=true&acao=editar&form_id=" + cfop_id,
        url: "view/configuracao/cfop/cfop_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_cfop").modal('show');

        },
    });

})


