$("#alterar_status").click(function () {
    var status = $('#status').val()
    var pedido_id = $(this).attr("pedido_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar o status desse pedido?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update_status(pedido_id, status)
        }
    })

})

function update_status(pedido_id, status) {
    $.ajax({
        type: "POST",
        data: "consultar_pedido=true&acao=update_status&status=" + status + "&pedido_id=" + pedido_id + "&tempo_entrega=",
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

            localStorage.setItem('pedido_' + pedido_id + '', JSON.stringify(false)); // Define o valor para false

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

}

$(".cancelar_pedido").click(function () {
    var pedido_id = $(this).attr("pedido_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar esse pedido ?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&acao=cancelar_pedido_delivery&mensagem=Favor, selecione o autorizador para completar a ação",
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_cancelar_pedido_delivery")
                        + $('#autorizar_acao').attr('pedido_id', pedido_id);

                },
            })
        }
    })
})

$("#open_whats").click(function (e) {

    var phoneNumber = $(this).attr("telefone");
    var message = $("#mensagem_for_cliente").val()

    // Monta o esquema personalizado do WhatsApp
    var link = "whatsapp://send?phone=" + phoneNumber + "&text=" + encodeURIComponent(message);

    // Tenta abrir o link usando o esquema personalizado
    window.open(link, '_blank');

    // Impede o comportamento padrão do link
    e.preventDefault();
})

$(".comanda").click(function () {

    var pedido_id = $(this).attr("pedido_id")

    var janela = "view/delivery/pedido/comanda/modelo_2.php?comanda2=true&pedido_id=" + pedido_id
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');

})



// function alterar_produto_venda(itens, codigo_nf, id_user_logado, user_logado, autorizador) {

//     let itensJSON = JSON.stringify(itens); //codificar para json
//     $.ajax({
//       type: "POST",
//       data: {
//         venda_mercadoria: true,
//         acao: "validar_alteracao_produto",
//         itens: itensJSON,
//         cd_nf: codigo_nf,
//         id_user: id_user_logado,
//         user_nome: user_logado,
//         check_autorizador: autorizador,
//       },
//       url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
//       async: false
//     }).then(sucesso, falha);

//     function sucesso(data) {

//       $dados = $.parseJSON(data)["dados"];
//       if ($dados.sucesso == true) {//se tiver ok com as informações do produto
//         Swal.fire({
//           position: 'center',
//           icon: 'success',
//           title: $dados.title,
//           showConfirmButton: false,
//           timer: 3500
//         })
//         tabela_produtos(codigo_nf)
//         $('.fechar_modal_autorizado').trigger('click'); // clicar automaticamente para realizar fechar o modal do autorizador
//         $('.fechar_modal_alterar_item').trigger('click'); // clicar automaticamente para realizar fechar o modal do produto

//         //   resetarValoresProdutos()
//       } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
//         $.ajax({
//           type: 'GET',
//           data: "autorizar_acao=true&mensagem=" + $dados.title,
//           url: "view/include/autorizacao/autorizar_acao.php",
//           success: function (result) {
//             return $(".alert").html(result)
//               + $("#modal_autorizar_acao").modal('show')
//               + $("#autorizar_acao").addClass("autorizar_desconto_alterar_prd_venda");

//           },

//         });


//       } else {//sucesso == false
//         Swal.fire({
//           icon: 'error',
//           title: 'Verifique!',
//           text: $dados.title,
//           timer: 7500,

//         })
//       }
//     }





$(".receber_nf").click(function () {

    var pedido_id = $(this).attr("pedido_id")
    var tipo_pagamento = $(this).attr("tipo_pagamento")

    if (tipo_pagamento == "cartao") {
        $.ajax({
            type: 'GET',
            data: "recebimento_nf=true&tipo=" + tipo_pagamento + "&nf_id=" + pedido_id,
            url: "view/include/recebimento_nf/tela_recebimento.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_recebimento_nf").modal('show');
            },
        });
    }

});