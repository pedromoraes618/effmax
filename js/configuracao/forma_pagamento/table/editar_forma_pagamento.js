//abrir a pagina de edição do formulario, pegando o id 
$(".editar_forma_pagamento").click(function () {

    var forma_de_pagamento_id = $(this).attr("forma_de_pagamento_id")

    $.ajax({
        type: 'GET',
        data: "forma_pagamento=true&acao=editar&form_id=" + forma_de_pagamento_id,
        url: "view/configuracao/forma_pagamento/forma_pagamento_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_forma_pagamento").modal('show');

        },
    });
    
})


