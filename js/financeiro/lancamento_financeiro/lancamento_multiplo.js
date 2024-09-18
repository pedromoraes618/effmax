//modal para consultar o cliente
$("#modal_parceiro").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_financeiro=true&tipo=RECEITA",
        url: "view/include/parceiro/pesquisa_parceiro.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_parceiro").modal('show');

        },
    });
});



$("#lancamento_multiplo").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja realizar o lançamento desses valores?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = create_lanc_multiplo(formulario)
        }
    })
})

//mostrar as informações no formulario show
function create_lanc_multiplo(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=create_lancamento_multiplo&" + dados.serialize(),
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 3500
            })


            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta

            setTimeout(function () {
                $('#fechar_modal_lanc_multiplo').trigger('click'); // clicar automaticamente para fechar o modal
                //  $(".receber_nf").remove()
            }, 1000);

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

/*previa das parcelas faturado */
$("#previa_parcelas").click(function () {
    var n_parcelas = $("#nparcelas").val()
    var primeira_parcela = $("#primeira_parcela").val()
    var intervalo = $("#intervalo").val()
    var forma_pagamento = $("#forma_pagamento").val()
    var valor_liquido = $("#valor_liquido").val()


    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=previa_parcelas_lancamento_multiplo&n_pacelas=" + n_parcelas +
            "&primeira_parcela=" + primeira_parcela +
            "&intervalo=" + intervalo + "&forma_pgt_id=" + forma_pagamento + "&valor_liquido=" + valor_liquido,
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            $(".dtvencimento").val("")
            $(".doc").val("")
            $(".valor").val("")
            var parcelas = $dados.valores;
            input_qtd = 0;
            // Percorre todas as parcelas e exibe no console
            for (var i = 0; i < parcelas.length; i++) {
                var data_vencimento = parcelas[i].dtvencimento
                var valor = parcelas[i].valor
                var doc = parcelas[i].doc

                var input_qtd = input_qtd + 1;
                $("#" + input_qtd + "dtvencimento").val(data_vencimento)
                $("#" + input_qtd + "valor").val(valor)
                $("#" + input_qtd + "doc").val(doc)
            }
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

})


$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_lancamento_multiplo'), // Para garantir que funcione no modal
        width: '100%',

    });
});
