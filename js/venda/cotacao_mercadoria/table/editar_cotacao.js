

//editar venda
$(".editar_cotacao").click(function () {

    var codigo_nf = $(this).data("codigo-nf")
    var cotacao_id = $(this).data("cotacao-id")

    $.ajax({
        type: 'GET',
        data: "cotacao_tela=true&form_id=" + cotacao_id + "&tipo=editar&codigo_nf=" + codigo_nf,
        url: "view/venda/cotacao_mercadoria/cotacao_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_cotacao").modal('show');;

        },
    });
})


$(".pdf_cotacacao").click(function () {
    var codigo_nf = $(this).data("codigo-nf")
    //svar janela = "view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=" + codigo_nf;
    imprimir(codigo_nf)
    // gerar_pdf(codigo_nf)
})

function imprimir(codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=imprimir&codigo_nf=" + codigo_nf,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
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
// function gerar_pdf(codigo_nf) {
//     $.ajax({
//         type: "POST",
//         data: "cotacao_mercadoria=true&acao=gerar_pdf&codigo_nf=" + codigo_nf,
//         url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
//         async: false
//     }).then(sucesso, falha);

//     function sucesso(data) {

//         $dados = $.parseJSON(data)["dados"];
//         if ($dados.sucesso == true) {
//             console.log($dados.title)
//             Swal.fire({
//                 position: 'center',
//                 icon: 'success',
//                 title: $dados.title,
//                 showConfirmButton: false,
//                 timer: 3500
//             })
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Verifique!',
//                 text: $dados.title,
//                 timer: 7500,
//             })

//         }
//     }

//     function falha() {
//         console.log("erro");
//     }

// }