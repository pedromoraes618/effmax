//abrir a pagina de edição do formulario, pegando o id 
$(".editar").click(function () {
    var cupom_id = $(this).attr("id")
 
    $.ajax({
        type: 'GET',
        data: "cupom_tela=true&form_id=" + cupom_id,
        url: "view/ecommerce/configuracao/cupom_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_cupoom").modal('show');
        },
    });

})
