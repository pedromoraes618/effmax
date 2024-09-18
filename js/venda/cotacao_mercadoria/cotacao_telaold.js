// $("#voltar").click(function () {
//     $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta

// })


const formulario_post = document.getElementById("cotaca_mercadoria");
let id_formulario = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')
let tipo = document.getElementById('tipo')
let codigo_nf = document.getElementById('codigo_nf')
var id_user_logado = $("#id_user_logado").val()





/*funcões */
function resetarValoresProdutos() {
    $("#produto_id").val('')
    $("#descricao_produto").val('')
    $("#unidade").val('')
    $("#estoque").val('')
    $("#quantidade").val('')
    $("#valor_total").val('')
    $("#preco_venda").val('')
    $("#desconto").val('')
    $("#preco_venda_atual").val('')

}


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

//modal para adicionar parceiro avulso
$("#modal_parceiro_avulso").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_parceiro_avulso=true",
        url: "view/include/parceiro_avulso/adicionar_parceiro.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_adiciona_parceiro_avulso").modal('show');

        },
    });
});


//modal para consultar o produto
$("#modal_produto").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_produto=true&tipo_produto=1&operacao_produto=venda",
        url: "view/include/produto/pesquisa_produto.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_produto").modal('show');

        },
    });
});

//modal para adicionar observacao
$("#modal_observacao").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_observacao=true",
        url: "view/include/observacao/adicionar_observacao.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_adiciona_observacao").modal('show');

        },
    });
});


$(".fechar_tela_cotacao").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal
})




function generateUniqueId() {
    var timestamp = Date.now().toString(); // Obter o timestamp atual como uma string
    var randomNum = Math.random().toString().substr(2, 5); // Gerar um número aleatório e extrair uma parte dele
    var uniqueId = timestamp + randomNum; // Concatenar o timestamp e o número aleatório
    return uniqueId;
}

function verificar_status_nf() {//se o usuario esquecer de iniciar a venda o sistema irá iniciar automaticameente
    if (codigo_nf.value == '') {
        $("#codigo_nf").val(generateUniqueId())//gerar_codigo_nf automaticamente
        $(".title .status_momento_cotacao").html("Cotação Iniciada")//alterar a label cabeçalho
        $(".title .status_momento_cotacao").css("display", "")//display block para a label que ira informar o usuario qual é o status momento da venda
        setTimeout(function () {
            $(".title .status_momento_cotacao").html("Cotacação em Andamento..")//alterar a label cabeçalho
        }, 3000);
        if ($("#vendedor option[value='" + id_user_logado + "']").length > 0) {//selecionar o vendedor automaticamente
            $("#vendedor").val(id_user_logado)
        }
        $("#status_cotacao").val(1)
        tabela_produtos(codigo_nf.value);
    }
}


//retorna os dados para o formulario
if (id_formulario.value == "") {
    $(".title .sub-title").html("Lançar cotação")//alterar a label cabeçalho
    // var momento_venda = document.getElementById("momento_venda")//status da venda//1-iniciado 2-finalizado 3-edicao
    $(".title .status_momento_cotacao").css("display", "none")//display none para a label que ira informar o usuario qual é o status momento da venda

    $('#iniciar_cotacao').click(function () {//iniciar venda
        formulario_post.reset(); // redefine os valores do formulário
        $("#codigo_nf").val(generateUniqueId())//gerar_codigo_nf automaticamente
        $(".title .status_momento_cotacao").html("Cotação Iniciada")//alterar a label cabeçalho
        $(".title .status_momento_cotacao").css("display", "")//display block para a label que ira informar o usuario qual é o status momento da venda
        setTimeout(function () {
            $(".title .status_momento_cotacao").html("Cotacação em Andamento..")//alterar a label cabeçalho
        }, 3000);
        if ($("#vendedor option[value='" + id_user_logado + "']").length > 0) {//selecionar o vendedor automaticamente
            $("#vendedor").val(id_user_logado)
        }
        $("#status_cotacao").val(1)
        tabela_produtos(codigo_nf.value);
    })



} else {//editar venda
    $(".title .sub-title").html("Alterar Cotação")//alterar a label cabeçalho
    $(".title .status_momento_cotacao").html("Alteração em andamento")//alterar a label cabeçalho
    // show(id_formulario.value) // funcao para retornar os dados para o formulario
    $("#iniciar_cotacao").css("display", "none");//inicar venda em none

    show(id_formulario.value, codigo_nf.value)//dados da nf
    tabela_produtos(codigo_nf.value);
}


$("#cotaca_mercadoria").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);

    if (codigo_nf.value == "") {
        verificar_status_nf()
    }

    create(formulario, codigo_nf.value)

})
$("#alterar_cotacao").click(function () {
    /*pegar os valores  */
    var data_fechamento = $("#data_fechamento").val()
    var vendedor = $("#vendedor").val()
    var status_cotacao = $("#status_cotacao").val()
    var validade = $("#validade").val()
    var att = $("#att").val()
    var parceiro_descricao = $("#parceiro_descricao").val()
    var parceiro_id = $("#parceiro_id").val()
    var observacao = $("#observacao").val()

    //  var referencia = $("#referencia").val()

    var dados = {
        id_user_logado: id_user_logado,
        codigo_nf: codigo_nf.value,
        data_fechamento: data_fechamento,
        vendedor: vendedor,
        status_cotacao: status_cotacao,
        validade: validade,
        att: att,
        parceiro_descricao: parceiro_descricao,
        parceiro_id: parceiro_id,
        observacao: observacao
    };


    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa cotação?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update(dados, codigo_nf.value)
        }
    })

})
$(".imprimir").click(function () {
    imprimir(codigo_nf.value)
})

$("#adicionar_desconto").click(function () {
    var desconto = $("#valor_desconto").val();
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar o desconto dessa cotação?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            adicionar_desconto(desconto, codigo_nf.value, "false")
        }
    })

})

$("#gerar_venda").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Deseja gerar uma venda dessa cotação?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            // Pergunta adicional
            Swal.fire({
                title: 'Escolha a opção',
                text: 'Deseja gerar a venda apenas com os itens ganhos ou com todos os itens?',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Itens Ganhos',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#157347',
                confirmButtonText: 'Todos os Itens'
            }).then((itemsResult) => {
                if (itemsResult.isConfirmed) {
                    // Gerar venda com todos os itens
                    gerar_venda(codigo_nf.value, "todos"); // Adicione o parâmetro para indicar todos os itens
                } else if (itemsResult.dismiss === Swal.DismissReason.cancel) {
                    // Gerar venda apenas com itens ganhos
                    gerar_venda(codigo_nf.value, "ganhos"); // Adicione o parâmetro para indicar apenas itens ganhos
                }
            });
        }
    });

})


$("#cancelar_cotacao").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa cotação?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelar_cotacao(codigo_nf.value)
        }
    })
})

function create(dados, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=create&" + dados.serialize() + "&check_autorizador=false",
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            tabela_produtos(codigo_nf)
            resetarValoresProdutos()
            show_valores(codigo_nf)
        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_desconto_incluir_prd_cotacao");
                },
            });
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
function show_valores(codigo_nf) {
    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=show_valores&codigo_nf=" + codigo_nf,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#valor_produtos").val($dados.valores['valor_produtos'])
            if ($("#valor_desconto").val() == 0) {
                $("#valor_desconto").val($dados.valores['valor_desconto'])
            }
            $("#valor_liquido_cotacao").val($dados.valores['valor_liquido'])
            calcular_valor_total_cotacao()//calcular o valor total do pedido
        }
    }

    function falha() {
        console.log("erro");
    }
}



function gerar_venda(codigo_nf, opcao_item) {
    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=gerar_venda&codigo_nf=" + codigo_nf + "&opcao_item=" + opcao_item,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
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

function update(dados, codigo_nf) {
    let dadosJSON = JSON.stringify(dados); //codificar para json
    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=update&dados=" + dadosJSON,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
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
            tabela_produtos(codigo_nf)
            show_valores(codigo_nf)
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

function cancelar_cotacao(codigo_nf) {

    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=cancelar_cotacao&codigo_nf=" + codigo_nf,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
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
            $(".fechar_tela_cotacao").trigger('click')
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
function imprimir(codigo_nf) {

    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=imprimir&codigo_nf=" + codigo_nf,
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

function adicionar_desconto(desconto, codigo_nf, check_autorizador) {
    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=adicionar_desconto&desconto=" + desconto + "&codigo_nf=" + codigo_nf + "&check_autorizador=" + check_autorizador + "",
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
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
            tabela_produtos(codigo_nf)

        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_desconto_incluir_desconto_cotacao");
                },
            });
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

function tabela_produtos(codigo_nf) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "tabela_produto=true&codigo_nf=" + codigo_nf,
        url: "view/venda/cotacao_mercadoria/table/tabela_produtos.php",
        success: function (result) {
            return $(".tabela_externa").html(result)

        },
    })
}

//mostrar as informações no formulario show
function show(id, codigo_nf) {

    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=show&cotacao_id=" + id + "&codigo_nf=" + codigo_nf,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#data_movimento").val($dados.valores['data_movimento'])
            $("#vendedor").val($dados.valores['vendedor_id'])
            $("#status_cotacao").val($dados.valores['status_cotacao_id'])
            $("#validade").val($dados.valores['validade'])
            $("#att").val($dados.valores['att'])
            $("#valor_desconto").val($dados.valores['desconto'])
            $("#observacao").val($dados.valores['observacao'])
            $("#parceiro_id").val($dados.valores['parceiro_id'])
            $("#parceiro_descricao").val($dados.valores['parceiro_descricao'])
            $("#data_fechamento").val($dados.valores['data_fechamento'])

            // $('#cotaca_mercadoria .title').each(function () {
            //     $(this).append('<label class="bg-primary">Ct' + id + '</label>')
            // })
            show_valores(codigo_nf)
            // $('#cotaca_mercadoria .group-btn-acao').each(function () {
            //     $(this).prepend(")
            // })

            // $('#cotaca_mercadoria .group-btn-acao').each(function () {
            //     $(this).prepend("<button type='button' class='btn btn-sm btn-info btn-gerar-venda' id='gerar_venda'>Gerar Venda</button>");
            // });
        }
    }

    function falha() {
        console.log("erro");
    }

}


//calulcar o valor liquido do produto
function calcular_valor_total_cotacao() {
    var valor_produtos = $('#valor_produtos').val() || "0"; // Garantindo que seja string para substituição
    var desconto = $('#valor_desconto').val() || "0"; // Garantindo que seja string para substituição

    // Verificando se tem uma vírgula e substituindo pelo ponto, após isso é transformado para número (parseFloat)
    if (desconto.includes(",")) {
        desconto = desconto.replace(",", ".");
    }
    desconto = parseFloat(desconto);

    if (valor_produtos.includes(",")) {
        valor_produtos = valor_produtos.replace(",", ".");
    }
    valor_produtos = parseFloat(valor_produtos);

    // Calculando o valor líquido
    var valorLiquido = valor_produtos - desconto;
    valorLiquido = valorLiquido.toFixed(2);

    // Atribuindo o valor líquido ao campo correspondente
    $('#valor_liquido_cotacao').val(valorLiquido);

    // Opcional: Alerta para verificar o valor
    // alert(valorLiquido);
}



function delete_item(codigo_nf, id_item_nf, id_produto, id_user_logado) {

    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=delete_item&id_item_cotacao=" + id_item_nf + "&id_produto=" + id_produto + "&codigo_nf=" + codigo_nf + "&id_user_logado=" + id_user_logado,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
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
            tabela_produtos(codigo_nf);//recarregar a tabela de produtos
            show_valores(codigo_nf)

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


//calulcar o valor liquido do produto
function calcular_valor_total() {

    var quantidade = $('#quantidade').val();
    var preco_venda = $('#preco_venda').val();
    // var preco_venda_atual = $('#preco_venda_atual').val();

    if (quantidade) {//verificando se tem um virgula e substituindo pelo ponto, apos isso e transformado para numero(parsefloat)
        if (quantidade.includes(",")) {
            quantidade = quantidade.replace(",", ".");
        }
        quantidade = parseFloat(quantidade)
    }

    if (preco_venda) {
        if (preco_venda.includes(",")) {
            preco_venda = preco_venda.replace(",", ".");
        }
        preco_venda = parseFloat(preco_venda)
    }


    if (quantidade && preco_venda) {
        var valorFinal = (quantidade * preco_venda);
        valorFinal = (valorFinal.toFixed(2));

        $('#valor_total').val(valorFinal);
    } else {

        $('#valor_total').val(0);
    }



}


//calulcar o valor liquido do produto
function calcular_desconto() {
    var preco_venda = $('#preco_venda').val();
    var preco_venda_atual = $('#preco_venda_atual').val();

    if (preco_venda != preco_venda_atual) {//verificar se o valor do preco de venda foi alterado
        if (preco_venda) {
            if (preco_venda.includes(",")) {
                preco_venda = preco_venda.replace(",", ".");
            }
            preco_venda = parseFloat(preco_venda)
        }

        if (preco_venda) {

            valor_final = ((preco_venda * 100) / preco_venda_atual)
            valor_final = (100 - valor_final)
            valor_final = (valor_final.toFixed(2));
            $('#desconto').val(valor_final);
        }

    }

}

//calulcar o valor liquido do produto
function calcular_preco_venda() {
    var desconto = $('#desconto').val();
    var preco_venda_atual = $('#preco_venda_atual').val();

    if (desconto) {//verificar se o valor do preco de venda foi alterado
        if (desconto) {
            if (desconto.includes(",")) {
                desconto = desconto.replace(",", ".");
            }
            desconto = parseFloat(desconto)
        }
        valor_final = preco_venda_atual - (desconto / 100) * preco_venda_atual;
        valor_final = (valor_final.toFixed(2));
        $('#preco_venda').val(valor_final);
    }

}
$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_adicionar_cotacao'), // Para garantir que funcione no modal
        width: '100%',

    });
});