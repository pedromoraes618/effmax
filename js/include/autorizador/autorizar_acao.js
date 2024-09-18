



$("#autorizar_acao").click(function (e) {
    e.preventDefault()
    if ($("#autorizar_acao").hasClass("autorizar_desconto_prd_venda")) {//verificar se existe a classe
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()

        /*pegar os valores  */
        var data_movimento = $("#data_movimento").val()
        var id_produto = $("#produto_id").val()
        var descricao_produto = $("#descricao_produto").val()
        var unidade = $("#unidade").val()
        var quantidade = $("#quantidade").val()
        var preco_venda = $("#preco_venda").val()
        var valor_total = $("#valor_total").val()
        var referencia = $("#referencia").val()
        var estoque = $("#estoque").val()
        var preco_venda_atual = $("#preco_venda_atual").val()

        var itens = {
            data_movimento: data_movimento,
            id_produto: id_produto,
            descricao_produto: descricao_produto,
            unidade: unidade,
            estoque: estoque,
            preco_venda: preco_venda,
            quantidade: quantidade,
            valor_total: valor_total,
            referencia: referencia,
            preco_venda_atual: preco_venda_atual,

        };


        autorizar_acao_incluir_prd_venda(usuario_id, senha, itens)//funcao validador de autorizacao

    }

    if ($("#autorizar_acao").hasClass("autorizar_desconto_alterar_prd_venda")) {
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var codigo_nf = $("#codigo_nf").val()

        var id_produto = $("#id_produto_item").val()
        var id_item_nf = $("#id_item_nf").val()
        var descricao_produto = $("#descricao_item").val()
        var unidade = $("#unidade_item").val()
        var quantidade = $("#quantidade_item").val()
        var preco_venda = $("#preco_venda_item").val()
        var valor_total = $("#valor_total_item").val()
        //   var referencia = $("#referencia").val()
        //  var referencia = $("#referencia").val()
        var itens = {
            id_produto: id_produto,
            id_item_nf: id_item_nf,
            descricao_produto: descricao_produto,
            unidade: unidade,
            preco_venda: preco_venda,
            quantidade: quantidade,
            valor_total: valor_total,
            referencia: "",
        };
        autorizar_acao_alterar_prd_venda(usuario_id, senha, itens, codigo_nf)//funcao validador de autorizacao
    }

    if ($("#autorizar_acao").hasClass("autorizar_desconto_incluir_prd_cotacao")) {//verificar se existe a classe cotacao
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var codigo_nf_prd = $("#codigo_nf").val()
        var produto_id = $("#produto_id").val()
        var descricao_produto = $("#descricao_produto").val()
        var quantidade = $("#quantidade").val()
        var preco_venda = $("#preco_venda").val()
        var valor_total = $("#valor_total").val()
        var prazo_entrega = $("#prazo_entrega").val()
        var vendedor = $("#vendedor").val()

        var itens = {
            codigo_nf: codigo_nf_prd,
            produto_id: produto_id,
            descricao_produto: descricao_produto,
            preco_venda: preco_venda,
            quantidade: quantidade,
            valor_total: valor_total,
            prazo_entrega: prazo_entrega,
            vendedor: vendedor,
        };
        autorizar_acao_prd_cotacao(usuario_id, senha, itens, "INCLUIR")//funcao validador de autorizacao
    }
    if ($("#autorizar_acao").hasClass("autorizar_desconto_alterar_prd_cotacao")) {//verificar se existe a classe cotacao
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()

        var codigo_nf = $("#codigo_nf").val()
        var produto_id = $("#id_produto_item").val()
        var id_item_nf = $("#id_item_nf").val()
        var descricao_produto = $("#descricao_item").val()
        var preco_venda = $("#preco_venda_item").val()
        var quantidade = $("#quantidade_item").val()
        var status_produto = $("#status_produto").val()
        var prazo_entrega_produto = $("#prazo_entrega_produto").val()

        var itens = {
            produto_id: produto_id,
            id_item_nf: id_item_nf,
            descricao_produto: descricao_produto,
            preco_venda: preco_venda,
            quantidade: quantidade,
            status_produto: status_produto,
            prazo_entrega_produto: prazo_entrega_produto,
        };


        autorizar_acao_desconto_alterar_produto_cotacao(usuario_id, senha, itens)//funcao validador de autorizacao
    }


    if ($("#autorizar_acao").hasClass("autorizar_desconto_incluir_desconto_cotacao")) {
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var codigo_nf = $("#codigo_nf").val()

        autorizar_acao_desconto_cotacao(usuario_id, senha, codigo_nf)//funcao validador de autorizacao
    }
    if ($("#autorizar_acao").hasClass("autorizar_desconto_incluir_desconto_ordem_servico")) {
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var form_id = $("#id").val()
        autorizar_acao_desconto_ordem_servico(usuario_id, senha, form_id)//funcao validador de autorizacao
    }
    if ($("#autorizar_acao").hasClass("autorizar_cancelar_pedido_delivery")) {//cancelamento de pedido delivery
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var pedido_id = $(this).attr("pedido_id")
        autorizar_acao_cancelar_pedido_delivery(usuario_id, senha, pedido_id)
    }
    if ($("#autorizar_acao").hasClass("autorizar_cancelar_nf")) {//cancelamento de pedido delivery
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var id_formulario = $(this).attr("id_formulario")
        var codigo_nf = $(this).attr("codigo_nf")
        autorizar_acao_cancelar_nf(usuario_id, senha, id_formulario, codigo_nf)
    }

    /*cancelamento de taxa na ordem de serviço */
    if ($("#autorizar_acao").hasClass("autorizar_cancelamento_financeiro")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var form_id = document.getElementById("id")
        var lcf_id = $(this).data("lcf-id")
        tela = "cancelamento_taxa_os"
        autorizar_cancelar_lancamento_financeiro(usuario_id, senha, lcf_id, form_id.value, tela)
    }

    /*cancelamento de lançamento na venda  */
    if ($("#autorizar_acao").hasClass("autorizar_cancelamento_financeiro_recebimento_nf")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var lcf_id = $(this).data("lcf-id")
        tela = "cancelamento_lancamento_recebimento_nf"
        autorizar_cancelar_lancamento_financeiro(usuario_id, senha, lcf_id, "", tela)
    }

    /*cancelamento de ordem de serviço  */
    if ($("#autorizar_acao").hasClass("autorizar_cancelar_os")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var os_id = $(this).data("form-id")
        tela = "cancelamento_os"
        autorizar_cancelar_nf(usuario_id, senha, os_id, tela)
    }

    /*cancelamento de pedido de compra  */
    if ($("#autorizar_acao").hasClass("autorizar_cancelar_pedido_compra")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var pedido_id = $(this).data("form-id")
        tela = "cancelamento_pedido_compra"
        autorizar_cancelar_nf(usuario_id, senha, pedido_id, tela)
    }
    /*cancelamento de cotação  */
    if ($("#autorizar_acao").hasClass("autorizar_cancelar_cotacao")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var pedido_id = $(this).data("form-id")
        tela = "cancelamento_cotacao"
        autorizar_cancelar_nf(usuario_id, senha, pedido_id, tela)
    }

    /*remover venda do faturamento  */
    if ($("#autorizar_acao").hasClass("autorizar_remover_faturamento_nf_saida")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var codigo_nf = $(this).data("codigo-nf")
        var tela = "remover_faturamento_nf_saida"
        autorizar_remover_faturamento_nf(usuario_id, senha, codigo_nf, tela)
    }

    /*remover ordem de serviço do faturamento  */
    if ($("#autorizar_acao").hasClass("autorizar_remover_faturamento_nf_os")) {//verificar se existe a classe cancelamento financeiro
        var usuario_id = $("#id_usuario_autorizador").val()
        var senha = $("#senha_autorizador").val()
        var form_id = $(this).data("form-id")
        var tela = "remover_faturamento_nf_os"
        autorizar_remover_faturamento_nf(usuario_id, senha, form_id, tela)
    }
});



function autorizar_acao_incluir_prd_venda(id_usuario, senha, itens) {
    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "venda",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            adicionar_produto_venda(itens, codigo_nf.value, id_user_logado, user_logado, "true");
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

function autorizar_acao_alterar_prd_venda(id_usuario, senha, itens, codigo_nf) {
    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "alterar_prd_venda",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            alterar_produto_venda(itens, codigo_nf, id_usuario, "", "true");
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


function autorizar_acao_desconto_cotacao(id_usuario, senha, codigo_nf) {//desconto na cotação
    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "alterar_desconto_cotacao",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            var desconto = $("#valor_desconto").val();
            adicionar_desconto(desconto, codigo_nf, "true")
            $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
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
function autorizar_acao_desconto_ordem_servico(id_usuario, senha, id) {//desconto na ordem de servico

    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "alterar_desconto_ordem_servico",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            var desconto = $("#desconto").val();
            adicionar_desconto(desconto, id, "true")
            $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
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

function autorizar_acao_desconto_alterar_produto_cotacao(id_usuario, senha, itens) {//desconto na cotação

    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "alterar_desconto_alterar_produto_cotacao",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            // alterar_produto_cotacao(itens, codigo_nf, id_usuario, "true")//
            let itensJSON = JSON.stringify(itens); //codificar para json

            $.ajax({
                type: "POST",
                data: "cotacao_mercadoria=true&acao=alterar_produto&itens=" + itensJSON + "&id_user=" + id_usuario + "&check_autorizador=true",
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
                    $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
                    tabela_produtos(codigo_nf.value)

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


            $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
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
/*Cotação */


function autorizar_acao_prd_cotacao(id_usuario, senha, itens, tipo) {
    let itensJSON = JSON.stringify(itens); //codificar para json
    const itensJSONcod = encodeURIComponent(itensJSON);
    $.ajax({
        type: "POST",
        data: "cotacao_mercadoria=true&acao=validar_usuario_prduto&tipo=" + tipo + "&itens=" + itensJSONcod + "&usuario_id=" + id_usuario + "&senha=" + senha + "",
        url: "modal/venda/cotacao_mercadoria/gerenciar_cotacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            tabela_produtos(codigo_nf.value)
            resetarValoresProdutos()
            $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
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

function autorizar_acao_cancelar_nf(id_usuario, senha, id_formulario, codigo_nf) {//cancelamento de pedido delivery
    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "cancelamento_nf",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];

        if ($dados.sucesso == true) {

            $.ajax({
                type: "POST",
                data: "venda_mercadoria=true&acao=cancelar_nf&id_nf=" + id_formulario + "&codigo_nf=" + codigo_nf,
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
                    $('#venda_mercadoria .title').each(function () {
                        $(this).append('<label class="bg-danger">Venda cancelada</label>')
                    })
                    $("#cancelar_nf").remove(); // Remove o próprio botão
                    $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal

                    //   tabela_produtos(codigo_nf);//recarregar a tabela de produtos
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


function autorizar_acao_cancelar_pedido_delivery(id_usuario, senha, pedido_id) {//cancelamento de pedido delivery
    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "cancelamento_pedido_delivery",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];

        if ($dados.sucesso == true) {
            $.ajax({
                type: "POST",
                data: "consultar_pedido=true&acao=update_status&status=6&pedido_id=" + pedido_id,
                url: "modal/delivery/pedido/gerenciar_pedido.php",
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
                    $(".atualizar_pedidos").trigger('click'); // clicar automaticamente para realizar a consulta
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

            $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
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



function autorizar_cancelar_lancamento_financeiro(id_usuario, senha, lancamento_id, form_id, tela) {//autorizar cancelar lançamento

    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "cancelar_lancamento_financeiro",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            if (tela == "cancelamento_taxa_os") {
                cancelar_taxa(lancamento_id, form_id, true)
                $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal

            } else if (tela == "cancelamento_lancamento_recebimento_nf") {
                cancelar_lancamento(lancamento_id, true)
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



function autorizar_cancelar_nf(id_usuario, senha, form_id, tela) {//autorizar cancelar ordem de serviço //utilizar essa função para realizar cancelamentos de documento
    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: "cancelar_nf",
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            if (tela == "cancelamento_os") {
                cancelar_os(form_id, id_usuario, true)//ordem de serviço
            } else if (tela == "cancelamento_pedido_compra") {
                cancelar_pedido(form_id, id_usuario, true)//pedido de compra
            } else if (tela == "cancelamento_cotacao") {
                cancelar_cotacao(form_id, id_usuario, true)//cotação
            }
            $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
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


function autorizar_remover_faturamento_nf(id_usuario, senha, codigo_nf, tela) {//autorizar remover do faturamento 

    $.ajax({
        type: "POST",
        data: {
            autorizar_acao: true,
            acao: "validar_usuario",
            tela: tela,
            usuario_id: id_usuario,
            senha: senha
        },
        url: "modal/autorizador/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            if (tela == "remover_faturamento_nf_saida") {
                remover_nf_faturamento("", codigo_nf, id_usuario, true)
                $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal
            } else if (tela == "remover_faturamento_nf_os") {
                remover_nf_faturamento(codigo_nf, id_usuario, true)
                $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal

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