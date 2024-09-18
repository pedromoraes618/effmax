

//editar venda
$(".editar_pedido").click(function () {

    var codigo_nf = $(this).data("codigo_nf")
    var pedido_id = $(this).data("pedido_id")

    $.ajax({
        type: 'GET',
        data: "pedido_tela=true&form_id=" + pedido_id + "&tipo=editar&codigo_nf=" + codigo_nf,
        url: "view/compra/pedido_compra/pedido_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_pedido_compra").modal('show');;

        },
    });
})





$(".pdf_pedido").click(function () {
    var codigo_nf = $(this).data("codigo_nf")
    //svar janela = "view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=" + codigo_nf;
    imprimir(codigo_nf, 'pdf_pedido')
    // gerar_pdf(codigo_nf)
})


function imprimir(codigo_nf, tipo) {
    $.ajax({
        type: "POST",
        data: "formulario_pedido_compra=true&acao=imprimir&tipo=" + tipo + "&codigo_nf=" + codigo_nf,
        url: "modal/compra/pedido_compra/gerenciar_pedido.php",
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
