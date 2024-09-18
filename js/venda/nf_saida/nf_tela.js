

var id_nf_fiscal = document.getElementById("id")
var codigo_nf_fiscal = document.getElementById("codigo_nf")
tabela_produtos_nf(codigo_nf_fiscal.value);
show_nf(id_nf_fiscal.value, codigo_nf_fiscal.value)
show_valores(codigo_nf_fiscal.value)

$(".btn-close-modal").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal
})

//modal para consultar o parceiro
$("#modal_parceiro").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_parceiro=true",
        url: "view/include/parceiro/pesquisa_parceiro.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_parceiro").modal('show');

        },
    });
});

//modal para consultar o transportadora
$("#modal_transportadora").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_transportadora=true",
        url: "view/include/transportadora/pesquisa_transportadora.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_transportadora").modal('show');

        },
    });
});

//formulario para alterar
$("#nota_fsical_saida").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa nota?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            alterar(formulario)
        }
    })
})


function alterar(dados) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=update_nf_saida&" + dados.serialize(),
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
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
function show_nf(id, codigo_nf) {

    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=show&nf_id=" + id + "&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#numero_nf").val($dados.valores['numero_nf'])
            $("#data_emissao").val($dados.valores['data_emisao'])
            $("#finalidade").val($dados.valores['finalidade'])

            $("#observacao").val($dados.valores['observacao'])
            $("#fpagamento").val($dados.valores['id_forma_pagamento_venda'])
            $("#cfop").val($dados.valores['cfop'])
            $("#numero_pedido").val($dados.valores['numero_pedido'])
            $("#frete").val($dados.valores['frete'])

            $("#vfrete").val($dados.valores['valor_frete'])
            $("#desconto_nota").val($dados.valores['desconto_venda_real'])
            $("#vseguro").val($dados.valores['seguro'])
            $("#outras_despesas").val($dados.valores['outras_despesas'])
            $("#tipo_nota").val($dados.valores['tipo_documento_nf'])


            $("#chave_acesso").val($dados.valores['chave_acesso'])
            $("#protocolo").val($dados.valores['protocolo'])

            $("#parceiro_id").val($dados.valores['parceiro_id'])
            $("#transportadora_id").val($dados.valores['transportadora_id'])

            $("#parceiro_descricao").val($dados.valores['parceiro_descricao'])
            $("#transportadora_descricao").val($dados.valores['transportadora_descricao'])

            $("#placa").val($dados.valores['placa'])
            $("#uf_veiculo").val($dados.valores['uf_veiculo'])
            $("#quantidade_trp").val($dados.valores['quantidade_trp'])
            $("#especie").val($dados.valores['especie_trans'])
            $("#peso_bruto").val($dados.valores['peso_bruto'])
            $("#peso_liquido").val($dados.valores['peso_liquido'])

            $("#chave_acesso_ref").val($dados.valores['chave_acesso_referencia'])
            $("#numero_nf_ref").val($dados.valores['numero_nf_ref'])


            $("#valor_pis_servico").val($dados.valores['valor_pis_servico'])
            $("#valor_cofins_servico").val($dados.valores['valor_cofins_servico'])
            $("#valor_deducoes").val($dados.valores['valor_deducoes'])
            $("#valor_inss").val($dados.valores['valor_inss'])
            $("#valor_ir").val($dados.valores['valor_ir'])
            $("#valor_csll").val($dados.valores['valor_csll'])
            $("#valor_iss").val($dados.valores['valor_iss'])
            $("#valor_outras_retencoes").val($dados.valores['valor_outras_retencoes'])
            $("#valor_base_calculo").val($dados.valores['valor_base_calculo'])
            $("#valor_aliquota").val($dados.valores['valor_aliquota'])
            $("#valor_desconto_condicionado").val($dados.valores['valor_desconto_condicionado'])
            $("#valor_desconto_incondicionado").val($dados.valores['valor_desconto_incondicionado'])
            $("#atividade_id").val($dados.valores['atividade_id'])
            $("#natureza_operacao_servico").val($dados.valores['natureza_operacao_servico'])
            $("#parceiro_terceirizado_id").val($dados.valores['intermediario_id'])



     

            

            if ($dados.valores['visualizar_duplicata'] == 1) {
                $('#visualzar_duplicatas').attr('checked', true);
            }
            if ($dados.valores['retem_iss'] == 1) {
                $('#retem_iss').attr('checked', true);
            }

            const arrayDuplicatas = $dados.valores['duplicatas']



            const duplicatasContainer = $('.duplicatas');
            // Limpa o conteúdo atual da classe duplicatas
            duplicatasContainer.empty();
            for (let i = 0; i < arrayDuplicatas.length; i++) {
                const duplicata = arrayDuplicatas[i];
                // Faça algo com a duplicata, por exemplo:
                //  console.log(duplicata.data_vencimento, duplicata.numero_duplicata, duplicata.valor_liquido);

                // Cria as divs com a classe col-md
                const divDataVencimento = $('<div>').addClass('col-md ').append($('<input>').addClass('form-control').attr('disabled', 'yes').attr('type', 'date').val(duplicata.data_vencimento));
                const divNumeroDuplicata = $('<div>').addClass('col-md').append($('<input>').addClass('form-control').attr('disabled', 'yes').attr('type', 'text').val(duplicata.numero_duplicata));
                const divValorLiquido = $('<div>').addClass('col-md').append($('<input>').addClass('form-control').attr('disabled', 'yes').attr('type', 'text').val(duplicata.valor_liquido));


                // Adiciona as divs à classe duplicatas
                duplicatasContainer.append(divDataVencimento, divNumeroDuplicata, divValorLiquido);

            }

            /*tela */
            /*duplicatas */
            if (arrayDuplicatas.length > 0) {
                $('#panelsStayOpen-collapseOne').addClass('show');
            }
            /* Transportadora */
            if ($dados.valores['transportadora_id']) {
                $('#panelsStayOpen-collapseTwo').addClass('show');
            }

            /* Devolução */
            if ($dados.valores['chave_acesso_referencia']) {
                $('#panelsStayOpen-collapseThree').addClass('show');
            }

            /* Informações adicionais */
            if ($dados.valores['observacao']) {
                $('#panelsStayOpen-collapseFour').addClass('show');
            }

            /* Informações adicionais */
            if ($dados.valores['serie_nf']=="NFS") {
                $('#panelsStayOpen-collapseSix').addClass('show');
            }
        }
    }

    function falha() {
        console.log("erro");
    }

}

//informa aos campos os valores de imposto resumidamente no corpo da nota
function show_valores(codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=resumo_valores&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#bcicms_nota").val($dados.valores['bcicms_nota'])
            $("#icms_nota").val($dados.valores['icms_nota'])
            $("#bcicms_sub_nota").val($dados.valores['bcicms_sub_nota'])
            $("#icms_sub_nota").val($dados.valores['icms_sub_nota'])
            $("#ipi_nota").val($dados.valores['ipi_nota'])

            $("#bciss_nota").val($dados.valores['bciss_nota'])
            $("#iss_nota").val($dados.valores['iss_nota'])
            $("#bccofins_nota").val($dados.valores['bccofins_nota'])
            $("#cofins_nota").val($dados.valores['cofins_nota'])
            $("#bcpis_nota").val($dados.valores['bcpis_nota'])
            $("#pis_nota").val($dados.valores['pis_nota'])

            $("#vlr_total_produtos").val($dados.valores['vlr_total_produtos'])

            $("#valor_base_calculo").val($dados.valores['base_calculo_servico'])
            $("#valor_iss").val($dados.valores['valor_iss'])

            calcularTotal()
        }
    }

    function falha() {
        console.log("erro");
    }

}
//modal para editar o item
$("#recalcular_nf").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja recalcular essa nota?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            recalcular_nf(id_nf_fiscal.value, codigo_nf_fiscal.value);
        }
    })
});


function recalcular_nf(id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=recalcular_nf&nf_id=" + id,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
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
            show_valores(codigo_nf)
            tabela_produtos_nf(codigo_nf)//retornar a tabela dos itens atualizado
            calcularTotal()

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


function tabela_produtos_nf(codigo_nf) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "tabela_produto=true&codigo_nf=" + codigo_nf,
        url: "view/venda/nf_saida/table/tabela_produtos.php",
        success: function (result) {
            return $(".tabela_externa").html(result)

        },
    })
}

calcularTotal()
function calcularTotal() {

    var outrasDespesas = ($("#outras_despesas").val())
    var totalProdutos = ($("#vlr_total_produtos").val())
    var desconto = ($("#desconto_nota").val())
    var vfrete = ($("#vfrete").val())
    var seguro = ($("#vseguro").val())
    var ipi_nota = ($("#ipi_nota").val())
    var icms_sub_nota = ($("#icms_sub_nota").val())

    if (outrasDespesas.includes(",")) {
        outrasDespesas = outrasDespesas.replace(",", ".");

    }
    if (totalProdutos.includes(",")) {
        totalProdutos = totalProdutos.replace(",", ".");

    }
    if (desconto.includes(",")) {
        desconto = desconto.replace(",", ".");

    }
    if (vfrete.includes(",")) {
        vfrete = vfrete.replace(",", ".");

    }
    if (seguro.includes(",")) {
        seguro = seguro.replace(",", ".");

    }
    if (ipi_nota.includes(",")) {
        ipi_nota = ipi_nota.replace(",", ".");

    }
    if (icms_sub_nota.includes(",")) {
        icms_sub_nota = icms_sub_nota.replace(",", ".");

    }
    outrasDespesas = parseFloat(outrasDespesas) || 0
    totalProdutos = parseFloat(totalProdutos) || 0
    desconto = parseFloat(desconto) || 0
    vfrete = parseFloat(vfrete) || 0
    seguro = parseFloat(seguro) || 0
    ipi_nota = parseFloat(ipi_nota) || 0
    icms_sub_nota = parseFloat(icms_sub_nota) || 0

    var valorTotal = icms_sub_nota + ipi_nota + seguro + vfrete + outrasDespesas + totalProdutos - desconto;

    $("#vlr_total_nota").val(valorTotal.toFixed(2));
}


// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });

// $('#cfop').select2({
//     dropdownParent: $('#modal_nf_fiscal')
// });
// $('#fpagamento').select2({
//     dropdownParent: $('#modal_nf_fiscal')
// });

$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_nf_fiscal'), // Para garantir que funcione no modal
        width: '100%',

    });
});


