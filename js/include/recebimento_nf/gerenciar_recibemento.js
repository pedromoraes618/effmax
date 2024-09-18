//retorna os dados para o formulario



var id_formulario = $("#nf_id").val()
if (id_formulario == "") {
} else {//exibir os dados na tela
    show(id_formulario) // funcao para retornar os dados para o formulario
}



$("#recebimento_nf").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    if (id_formulario != "") {//
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja realizar o recebimento dessa venda",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario)
            }
        })
    }
})

$("#recebimento_nf_faturado").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    if (id_formulario != "") {//
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja realizar o recebimento dessa venda",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create_faturado(formulario)
            }
        })
    }
})

/*previa das parcelas faturado */
$("#previa_parcelas").click(function () {
    var n_parcelas = $("#nparcelas").val()
    var primeira_parcela = $("#primeira_parcela").val()
    var intervalo = $("#intervalo").val()
    var forma_pagamento = $("#forma_pagamento").val()
    var valor_entrada = $("#valor_entrada").val()
    var nf_id = $("#nf_id").val()

    $.ajax({
        type: "POST",
        data: "recebimento_nf_saida=true&acao=previa_parcelas&nf_id=" + nf_id +
            "&n_pacelas=" + n_parcelas + "&primeira_parcela=" + primeira_parcela +
            "&intervalo=" + intervalo + "&forma_pgt_id=" + forma_pagamento +
            "&valor_entrada=" + valor_entrada,
        url: "modal/recebimento_nf/nf_saida/gerenciar_recebimento.php",
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


//mostrar as informações no formulario show
function show(id) {

    $.ajax({
        type: "POST",
        data: "recebimento_nf_saida=true&acao=show&nf_id=" + id,
        url: "modal/recebimento_nf/nf_saida/gerenciar_recebimento.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#numero_nf").val($dados.valores['serie_nf'] + $dados.valores['numero_nf'])
            $("#forma_pagamento").val($dados.valores['forma_pagamento'])
            $("#cliente").val($dados.valores['cliente'])

            $("#valor_liquido").val($dados.valores['valor_liquido'])
            $("#valor_credito").val($dados.valores['valor_credito'])
            $("#valor_adiantamento").val($dados.valores['valor_adiantamento'])
            $("#valor_a_receber").val($dados.valores['valor_a_receber'])
        }
    }

    function falha() {
        console.log("erro");
    }

}

consultar_tabela_historico_lancamentos(id_formulario)
function consultar_tabela_historico_lancamentos(id) {
    $.ajax({
        type: 'GET',
        data: "historico_recebimento_nf=true&form_id=" + id,
        url: "view/include/recebimento_nf/table/consultar_recebimento_nf.php",
        success: function (result) {
            return $(".tabela_historico_lancamento").html(result);

        },
    });
}


//mostrar as informações no formulario show
function create(dados) {
    $.ajax({
        type: "POST",
        data: "recebimento_nf_saida=true&acao=create&" + dados.serialize(),
        url: "modal/recebimento_nf/nf_saida/gerenciar_recebimento.php",
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
                $('#fechar_modal_recebimento').trigger('click'); // clicar automaticamente para fechar o modal
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

//mostrar as informações no formulario show
function create_faturado(dados) {
    $.ajax({
        type: "POST",
        data: "recebimento_nf_saida=true&acao=create_faturado&" + dados.serialize(),
        url: "modal/recebimento_nf/nf_saida/gerenciar_recebimento.php",
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
                $('#fechar_modal_recebimento').trigger('click'); // clicar automaticamente para fechar o modal
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



function valorPago() {
    var total = 0; // Inicialize a variável total

    // Use each para percorrer todos os campos de entrada com a classe "rec"
    $('.rec').each(function () {
        var valor = ($(this).val()) || 0; // Obter o valor como um número de ponto flutuante


        if (valor) {
            if (valor.includes(",")) {
                valor = valor.replace(",", ".");
            }
            valor = parseFloat(valor)

        }

        total += valor; // Adicionar o valor ao total
    });

    var valor_credito = parseFloat($("#valor_credito").val()) || 0;
    var valor_adiantamento = parseFloat($("#valor_adiantamento").val()) || 0;
    var valor_liquido = parseFloat($("#valor_liquido").val()) || 0;

    var restante = valor_liquido - valor_credito - valor_adiantamento - total;
    $("#restante").val(restante);

};
