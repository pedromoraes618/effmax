
if ($("#codigo_nf").val() == "") {
    $("#codigo_nf").val(generateUniqueId())
}


const formulario_post = document.getElementById("nota_fsical_entrada");
let id_form = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')
var codigo_nf = document.getElementById("codigo_nf")


function generateUniqueId() {
    var timestamp = Date.now().toString(); // Obter o timestamp atual como uma string
    var randomNum = Math.random().toString().substr(2, 5); // Gerar um número aleatório e extrair uma parte dele
    var uniqueId = timestamp + randomNum; // Concatenar o timestamp e o número aleatório
    return uniqueId;
}





$(".fechar_modal_compra").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta,
    $('.btn-close-importar-xml').trigger('click'); // clicar automaticamente para realizar a consulta

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

//modal para consultar o transportadora
$("#modal_produto_add_item").click(function () {
    $.ajax({
        type: 'GET',
        data: "produto_nf_entrada=true&codigo_nf=" + codigo_nf.value + "&acao=insert",
        url: "view/include/produto_nf/produto_nf_entrada.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_produto").modal('show');

        },
    });
});


if (id_form.value != "") {
    show(id_form.value, codigo_nf.value) // funcao para retornar os dados para o formulario
}

tabela_prod(codigo_nf.value)//retornar a tabela dos itens

//formulario para cadastro
$("#nota_fsical_entrada").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa compra?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = alterar(formulario, codigo_nf.value)
        }
    })
})


$("#finalizar_nf").click(function () {

    // Pegando os valores dos campos com base em seus IDs
    var id = $("#id").val();
    var codigo_nf = $("#codigo_nf").val();
    var dataEntrada = $("#data_entrada").val();
    var dataEmissao = $("#data_emissao").val();
    var numeroNF = $("#numero_nf").val();
    var fpagamento = $("#fpagamento").val();
    var cfop = $("#cfop").val();
    var serie = $("#serie").val();
    var frete = $("#frete").val();
    var chaveAcesso = $("#chave_acesso").val();
    var protocolo = $("#protocolo").val();
    var parceiroId = $("#parceiro_id").val();
    var transportadoraId = $("#transportadora_id").val();
    var vfrete = $("#vfrete").val();
    var vfreteConhecimento = $("#vfrete_conhecimento").val();
    var vseguro = $("#vseguro").val();
    var outrasDespesas = $("#outras_despesas").val();
    var descontoNota = $("#desconto_nota").val();
    var informacoes_adicionais = $("#informacoes_adicionais").val();

    var dataToSend = {
        id: id,
        codigo_nf: codigo_nf,
        data_entrada: dataEntrada,
        data_emissao: dataEmissao,
        numero_nf: numeroNF,
        fpagamento: fpagamento,
        cfop: cfop,
        serie: serie,
        frete: frete,
        chave_acesso: chaveAcesso,
        protocolo: protocolo,
        parceiro_id: parceiroId,
        transportadora_id: transportadoraId,

        vfrete: vfrete,
        vfrete_conhecimento: vfreteConhecimento,
        vseguro: vseguro,
        outras_despesas: outrasDespesas,
        desconto_nota: descontoNota,
        informacoes_adicionais: informacoes_adicionais,
    };

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja finalizar essa compra? ",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = finalizar_nf(dataToSend)
        }
    })
})
$("#remover_nf").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover essa compra? ",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = remover_nf(id_form.value)
        }
    })
})
$("#cancelar_nf").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa compra? ",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cancelar_nf(id_form.value)
        }
    })
})

$("#cancelar_provisionamento_nf").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar o provisionamento dessa compra? ",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cancelar_provisionamento(id_form.value)
        }
    })
})
function alterar(dados, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=alterar&" + dados.serialize(),
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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

            if ($dados.nf_id != "") {
                $('#fechar_modal_compra').trigger('click'); // clicar automaticamente para realizar a consulta
            }

            tabela_prod(codigo_nf)

            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta

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


function finalizar_nf(dados) {
    let dadosJSON = JSON.stringify(dados); //codificar para json
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=finalizar_nf&&dados=" + dadosJSON,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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


            if ($dados.nf_id != "") {
                $('#fechar_modal_compra').trigger('click'); // clicar PARA FECHAR MODAL
                $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
                $('.btn-close').trigger('click'); // clicar automaticamente PARA FECHAR MODAL

                /*abrir a tela de provisionamento de compra */
                $.ajax({
                    type: 'GET',
                    data: "provisionamento_nf_entrada=true&tipo=faturado&nf_id=" + $dados.nf_id,
                    url: "view/include/recebimento_nf/tela_provisionamento_faturado_entrada.php",
                    success: function (result) {
                        return $(".modal_show").html(result) + $("#modal_recebimento_nf_faturado").modal('show');
                    },
                });
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

}


function remover_nf(id) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=remover_nf&nf_id=" + id,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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
function cancelar_provisionamento(id) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=cancelar_provisonamento&nf_id=" + id,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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


function cancelar_nf(id) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=cancelar_nf&nf_id=" + id,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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

function delete_item(id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=delete_item&nf_id=" + id,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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
            tabela_prod(codigo_nf)//retornar a tabela dos itens atualizado
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
function show(id) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=show&form_id=" + id,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#numero_nf").val($dados.valores['numero_nf'])
            $("#data_entrada").val($dados.valores['data_entrada'])
            $("#data_emissao").val($dados.valores['data_emissao'])
            $("#fpagamento").val($dados.valores['fpagamento'])
            $("#cfop").val($dados.valores['cfop'])
            $("#serie").val($dados.valores['serie'])
            $("#frete").val($dados.valores['frete'])
            $("#chave_acesso").val($dados.valores['chave_acesso'])
            $("#protocolo").val($dados.valores['protocolo'])
            $("#parceiro_descricao").val($dados.valores['parceiro_descricao'])
            $("#parceiro_id").val($dados.valores['parceiro_id'])
            $("#transportadora_descricao").val($dados.valores['transportadora_descricao'])
            $("#transportadora_id").val($dados.valores['transportadora_id'])
            $("#vfrete").val($dados.valores['vfrete'])
            $("#vfrete_conhecimento").val($dados.valores['vfrete_conhecimento'])
            $("#vseguro").val($dados.valores['vseguro'])
            $("#outras_despesas").val($dados.valores['outras_despesas'])
            $("#desconto_nota").val($dados.valores['desconto_nota'])
            $("#vlr_total_nota").val($dados.valores['vlr_total_nota'])
            $("#informacoes_adicionais").val($dados.valores['observacao'])

            var status_nf = $dados.valores['status_nf']
            var status_provisionamento = $dados.valores['status_provisionamento']


            if (status_nf == '3') {//compra está cancelada
                $("#modal_produto_add_item").remove()//remover o botão de incluir itens
                $("#cancelar_nf").remove()//remover o botão de cancelar nf
                $("#cancelar_provisionamento_nf").remove()//remover o botão de provisionamento
                $("#finalizar_nf").remove()//remover o botão de finalar nf
                $("#editar_item_nf").remove()//remover o botão de alterar item

            }
            if (status_nf == '1') {//compra está finalizada
                $("#alterar_nf").remove()//remover o botão de alterar nf
                $("#finalizar_nf").remove()//remover o botão de alterar nf
                $("#modal_produto_add_item").remove()//remover o botão de incluir itens

            }

            if (status_provisionamento == "2") {//a compra está provisionada
                $("#modal_produto_add_item").remove()//remover o botão de incluir itens
            }
            if (status_provisionamento != "2") {//a compra está provisionada
                $("#cancelar_provisionamento_nf").remove()//remover o botão de provisionamento
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
        data: "formulario_nf_entrada=true&acao=resumo_valores&codigo_nf=" + codigo_nf,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
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
            $("#vlr_total_produtos").val($dados.valores['vlr_total_produtos'])

            calcularTotal()
        }
    }

    function falha() {
        console.log("erro");
    }

}

function tabela_prod(codigo_nf) {//consultar tabebla dos itens
    $.ajax({
        type: 'GET',
        data: "consultar_tabela_item_nf_entrada=true&codigo_nf=" + codigo_nf,
        url: "view/compra/compra_mercadoria/table/consultar_item.php",
        success: function (result) {
            return $(".tabela_externa").html(result)
        },
    });

    show_valores(codigo_nf)

}


function calcularTotal() {
    var icms_sub_nota = parseFloat($("#icms_sub_nota").val()) || 0;
    var ipi_nota = parseFloat($("#ipi_nota").val()) || 0;
    var outrasDespesas = parseFloat($("#outras_despesas").val()) || 0;
    var totalProdutos = parseFloat($("#vlr_total_produtos").val()) || 0;
    var desconto = parseFloat($("#desconto_nota").val()) || 0;
    var vfrete = parseFloat($("#vfrete").val()) || 0;
    var vseguro = parseFloat($("#vseguro").val()) || 0;

    var valorTotal = ipi_nota + icms_sub_nota + vseguro + vfrete + outrasDespesas + totalProdutos - desconto;

    $("#vlr_total_nota").val(valorTotal.toFixed(2));
}



// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });


$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_compra'), // Para garantir que funcione no modal
        width: '100%',

    });
});

