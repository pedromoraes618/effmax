
const formulario_post = document.getElementById("cotacao_mercadoria")
const formulario_item_post = document.getElementById("produto_cotacao")
let form_id = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')
let codigo_nf = document.getElementById('codigo_nf')



$(".fechar_tela_cotacao").click(function () {
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





function generateUniqueId() {
    var timestamp = Date.now().toString(); // Obter o timestamp atual como uma string
    var randomNum = Math.random().toString().substr(2, 5); // Gerar um número aleatório e extrair uma parte dele
    var uniqueId = timestamp + randomNum; // Concatenar o timestamp e o número aleatório
    return uniqueId;
}



function IniciarCotacao() {
    $("#codigo_nf").val(generateUniqueId())//gerar_codigo_nf automaticamente
    $(".title .status_momento_cotacao").html("Cotação iniciada")//alterar a label cabeçalho
    $(".title .status_momento_cotacao").css("display", "")//display block para a label que ira informar o usuario qual é o status momento da venda
    setTimeout(function () {
        $(".title .status_momento_cotacao").html("Cotação em Andamento..")//alterar a label cabeçalho
    }, 3000);

    $('#status_cotacao option[value="1"]').prop('selected', true).trigger('change');

    tabela_produtos(codigo_nf.value);
}

//retorna os dados para o formulario
if (form_id.value == "") {
    $(".title .sub-title").html("Lançar cotação")//alterar a label cabeçalho
    // var momento_venda = document.getElementById("momento_venda")//status da venda//1-iniciado 2-finalizado 3-edicao
    $(".title .status_momento_cotacao").css("display", "none")//display none para a label que ira informar o usuario qual é o status momento da venda

    $('#iniciar_cotacao').click(function () {//iniciar venda
        formulario_post.reset(); // redefine os valores do formulário
        IniciarCotacao()//conjunto de instruções para iniciar o pedido
    })

} else {//editar venda
    $(".title .sub-title").html("Alterar cotação")//alterar a label cabeçalho
    $(".title .status_momento_cotacao").html("Alteração em andamento")//alterar a label cabeçalho
    // show(id_formulario.value) // funcao para retornar os dados para o formulario
    $("#iniciar_cotacao").css("display", "none");//inicar venda em none
    // Criação do botão com classes do Bootstrap

    show(form_id.value)//dados da nf
    tabela_produtos(codigo_nf.value);
}



//formulario para cadastro
$("#cotacao_mercadoria").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (form_id.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar essa cotação?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (codigo_nf.value == "") {//adicionar produto
                    IniciarCotacao();
                }

                var retorno = create(formulario)
            }
        })
    } else {//editar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar essa cotação? ",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = update(formulario)
            }
        })
    }
})

//formulario para cadastro
$("#produto_cotacao").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    var item_id = $("#item_id").val()

    if (codigo_nf.value == "") {//adicionar produto
        IniciarCotacao();
    }

    if (item_id == '') {
        var retorno = item(formulario, codigo_nf.value)//inserir
    } else {//alterar o produto existente
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse item? ",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = item(formulario, codigo_nf.value)//alterar
            }
        })
    }
})


$(".gerar_pdf").click(function () {
    imprimir(codigo_nf.value)
})


$("#cancelar_cotacao").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa cotacao?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelar_cotacao(form_id.value, '', false)
        }
    })
})

function create(dados) {//create dados basicos
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=create&" + dados.serialize(),
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
            $("#documento").val($dados.response.documento)
            $("#id").val($dados.response.form_id)
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

function update(dados) {//create dados basicos
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=update&" + dados.serialize(),
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

//mostrar as informações no formulario show
function show(form_id) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=show&form_id=" + form_id,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            $("#codigo_nf").val($dados.valores['codigo_nf'])
            $("#data_movimento").val($dados.valores['data_movimento'])
            $("#data_fechamento").val($dados.valores['data_fechamento'])
            $("#documento").val($dados.valores['documento'])

            $("#numero_solicitacao").val($dados.valores['numero_solicitacao'])
            $("#vendedor_id").val($dados.valores['vendedor_id'])
            $("#status_cotacao").val($dados.valores['status_cotacao_id'])
            $("#validade").val($dados.valores['validade'])

            $("#parceiro_id").val($dados.valores['parceiro_id'])
            $("#parceiro_descricao").val($dados.valores['parceiro_descricao'])


            $("#valor_produtos").val($dados.valores['valor_bruto'])
            $("#valor_liquido_cotacao").val($dados.valores['valor_liquido'])
            $("#valor_desconto").val($dados.valores['valor_desconto'])
            $("#observacao").val($dados.valores['observacao'])

            show_valores(codigo_nf.value)//mostrar os valores 

        }
    }

    function falha() {
        console.log("erro");
    }

}

function cancelar_cotacao(form_id, usuario_id, check_autorizador) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=cancelar_cotacao&form_id=" + form_id +
            "&check_autorizador=" + check_autorizador + "&usuario_id=" + usuario_id,
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&acao=cancelar_cotacao&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_cancelar_cotacao")
                        + $("#autorizar_acao").attr("data-form-id", form_id)
                        + $('.modal-backdrop').remove();
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
        data: "formulario_cotacao=true&acao=show_valores&codigo_nf=" + codigo_nf,
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
            calcular_valor_total()//calcular o valor total do pedido
        }
    }

    function falha() {
        console.log("erro");
    }
}



//mostrar as informações do item para alteração
function show_item(form_id) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=show_item&form_id=" + form_id,
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#item_id").val($dados.valores['id'])
            $("#produto_id").val($dados.valores['produto_id'])
            $("#descricao_produto").val($dados.valores['descricao_produto'])
            $("#prazo_entrega").val($dados.valores['prazo_entrega'])
            $("#unidade").val($dados.valores['unidade'])
            $("#quantidade").val($dados.valores['quantidade'])
            $("#preco_venda").val($dados.valores['valor_unitario'])
            $("#valor_total").val($dados.valores['valor_total'])
            $("#situacao_produto").val($dados.valores['situacao_produto'])
        }
    }

    function falha() {
        console.log("erro");
    }

}

/*referente ao item */
function item(dados, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=item&" + dados.serialize() + "&codigo_nf=" + codigo_nf,
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

            formulario_item_post.reset(); // redefine os valores do formulário            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal
            $("#item_id").val('')
            $("#produto_id").val('')

            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal
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



function tabela_produtos(codigo_nf) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "tabela_produto=true&codigo_nf=" + codigo_nf,
        url: "view/venda/cotacao_mercadoria/table/tabela_produtos.php",
        success: function (result) {
            return $(".tabela_produtos").html(result)
        },
    })
}

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

function destroy_item(item_id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=destroy_item&form_id=" + item_id,
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
            formulario_item_post.reset()
            $("#item_id").val('')
            $("#produto_id").val('')
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




function gerar_venda(codigo_nf, opcao_item) {
    $.ajax({
        type: "POST",
        data: "formulario_cotacao=true&acao=gerar_venda&codigo_nf=" + codigo_nf + "&opcao_item=" + opcao_item,
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




//calulcar o valor liquido do produto
function calcular_valor_total_item() {
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
function calcular_valor_total() {
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

}


$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_cotacao'), // Para garantir que funcione no modal
        width: '100%',

    });
});