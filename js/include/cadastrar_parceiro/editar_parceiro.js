
$(document).ready(function () {
    var parceiro_id_value = document.getElementById('parceiro_id_value')
    $.ajax({
        type: 'GET',
        data: "editar_cliente=true&id_cliente=" + parceiro_id_value.value,
        url: "view/empresa/cliente/editar_cliente.php",
        success: function (result) {
            return $(".modal-body").html(result) + $("#voltar_consulta").css("display", "none");
        },
    });
})

