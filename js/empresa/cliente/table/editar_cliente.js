//ao clicar no botão cadastrar produto
$(".editar_cliente").click(function (e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "none")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "block")
    //  $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de cadastro
    var id_cliente = $(this).attr("id_cliente")
    $.ajax({
        type: 'GET',
        data: "editar_cliente=true&id_cliente=" + id_cliente,
        url: "view/empresa/cliente/editar_cliente.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
        },
    });
})


$(".consultar_historico").click(function () {
    var parceiro_id = $(this).attr("parceiro_id")

    $.ajax({
        type: 'GET',
        data: "consultar_historico_parceiro=true&parceiro_id=" + parceiro_id,
        url: "view/empresa/historico/consultar_historico.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_historico_parceiro").modal('show');
        },
    });
})



//modal para consultar os anexo
$(".modal_anexo").click(function () {
    var form_id = $(this).data("parceiro_id")

    if (form_id == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "É necesssario adicionar o parceiro",
            timer: 7500,
        })
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_anexo=true&form_id=" + form_id + "&tipo=parceiro",
            url: "view/include/anexo/consultar_anexo.php",
            success: function (result) {
                return $(".modal_show").html(result) + $("#modal_anexo").modal('show');

            },
        });
    }
});
