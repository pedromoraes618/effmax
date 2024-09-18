//retorna os dados para o formulario



var id_formulario = $("#nf_id").val()
if (id_formulario == "") {
} else {//exibir os dados na tela

    show(id_formulario) // funcao para retornar os dados para o formulario
}

$("#recebimento_nf_faturado").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    if (id_formulario != "") {//
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja realizar o recebimento dessa compra?",
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
        data: "recebimento_nf_entrada=true&acao=previa_parcelas&nf_id=" + nf_id +
            "&n_pacelas=" + n_parcelas + "&primeira_parcela=" + primeira_parcela +
            "&intervalo=" + intervalo + "&forma_pgt_id=" + forma_pagamento +
            "&valor_entrada=" + valor_entrada,
        url: "modal/recebimento_nf/nf_entrada/gerenciar_provisionamento.php",
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
        data: "recebimento_nf_entrada=true&acao=show&nf_id=" + id,
        url: "modal/recebimento_nf/nf_entrada/gerenciar_provisionamento.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#numero_nf").val($dados.valores['numero_nf'])
            $("#valor_liquido").val($dados.valores['valor_liquido'])
            $("#forma_pagamento").val($dados.valores['forma_pagamento'])
            $("#fornecedor").val($dados.valores['fornecedor'])
            // Função para verificar se um cookie específico existe

            /*verifica se existe o cokkie de provisonamento */
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith($dados.valores['numero_nf'] + '=')) {


                    const cookies = document.cookie.split(';');
                    for (let i = 0; i < cookies.length; i++) {
                        const cookie = cookies[i].trim();
                        if (cookie.startsWith($dados.valores['numero_nf'] + '=')) {
                            const cookieValue = cookie.substring($dados.valores['numero_nf'].length + 1);

                            const decodedCookieValue = decodeURIComponent(cookieValue);

                            // Analise a string JSON em um objeto JavaScript
                            const valoresRecuperados = JSON.parse(decodedCookieValue);

                            // Agora você pode acessar os valores no objeto
                        
                            for (q = 0; q < valoresRecuperados.length; q ++) {
                                count = q+1;
                                $("#" + count + "dtvencimento").val(valoresRecuperados[q].vencimento[0])
                                $("#" + count + "valor").val(valoresRecuperados[q].vlr_parcela)
                                $("#" + count + "doc").val(valoresRecuperados[q].titulo[0])
                            }

                        }
                    }
                }
            }

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
        data: "recebimento_nf_entrada=true&acao=create_faturado&" + dados.serialize(),
        url: "modal/recebimento_nf/nf_entrada/gerenciar_provisionamento.php",
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
                $('#fechar_modal_provisionamento').trigger('click'); // clicar automaticamente para fechar o modal
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
