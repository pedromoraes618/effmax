
$(".editar").click(function () {
    var parceiro_id = $(this).data("parceiro-id")
    var codigo_nf = $(this).data("codigo-nf")
    gerar_doc(parceiro_id, codigo_nf)
})


function gerar_doc(parceiro_id, codigo_nf) {

    $.ajax({
        type: "POST",
        data: "formulario_resumo_cobranca=true&acao=gerar_doc&parceiro_id=" + parceiro_id + "&codigo_nf=" + codigo_nf,
        url: "modal/financeiro/resumo_cobranca/gerenciar_resumo_cobranca.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            window.open($dados.title, 'popuppage',
                'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.title,
                timer: 7500,
            })

        }
    }

    function falha() {
        console.log("erro");
    }

}

//abrir a pagina de edição do formulario, pegando o id 
$(".contato").click(function () {
    var lancamento_id = $(this).attr("id")
    var parceiro_id = $(this).data("parceiro-id")
    var codigo_nf = $(this).data("codigo-nf")

    $.ajax({
        type: 'GET',
        data: "consultar_contato=true&acao=consultar&form_id=" + lancamento_id + "&codigo_nf=" + codigo_nf + "&parceiro_id=" + parceiro_id,
        url: "view/include/contato/contato_parceiro.php",
        success: function (result) {
            return $(".modal_show").html(result) + $("#modal_contato_parceiro").modal('show');

        },
    });
})


