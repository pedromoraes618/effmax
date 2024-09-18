
$(document).ready(function () {
    $.ajax({
        type: 'GET',
        data: "cadastro_cliente=true",
        url: "view/empresa/cliente/cadastro_cliente.php",
        success: function (result) {
            return $("#modal_tela_cadastrar_parceiro .modal-body").html(result) + $("#voltar_consulta").css("display","none");
        },
    });
})

