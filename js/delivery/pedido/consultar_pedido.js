
function playSound(audioName) {
    let audio = new Audio(audioName)
    audio.loop = false
    audio.play();
}

// // Verificar se existe algum valor true no localStorage
// function checkForTrueValue() {
//     for (let i = 0; i < localStorage.length; i++) {
//         const key = localStorage.key(i);
//         const value = localStorage.getItem(key);

//         if (value === 'true') {
//             return true;
//         }
//     }
//     return false;
// }

setInterval(atualizar_pedidos, 3 * 60 * 1000); // 3 minutos em milissegundos

function atualizar_pedidos() {

    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        const value = localStorage.getItem(key);

        if (value == 'true') {
            playSound("audio/alert.mp3");
        }
        localStorage.setItem(key, JSON.stringify(false)); // Define o valor para false

    }


    var id_pedido = localStorage.getItem("id_pedido");
    $.ajax({
        type: 'GET',
        data: "consultar_pedido=inicial",
        url: "view/delivery/pedido/consultar_pedido.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result)
                + $("#id_pedido" + id_pedido).trigger('click'); // clicar automaticamente para realizar a consulta
        },
    });
}


$(".status_pedido").click(function () {
    var id_pedido = $(this).attr("id_pedido");
    // Seleciona o elemento <span> com a classe desejada
    var spanElement = $(".active").find(".badge.text-bg-primary");

    // // Remove o elemento do local original
    spanElement.remove();

    spannew_elemento = '<span class="badge text-bg-primary">Selecionado</span>';
    // Adiciona o elemento ao novo local dentro da classe "selecionado"
    $(".selecionado" + id_pedido).append(spannew_elemento);
    localStorage.setItem("id_pedido", id_pedido);
    $.ajax({
        type: 'GET',
        data: "consultar_pedido=detalhado&pedido_id=" + id_pedido,
        url: "view/delivery/pedido/card/pedido_detalhado.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .card-externo").html(result);
        },
    });
    $('.bloco-right').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;
});


// for (var i = 0; i < localStorage.length; i++) {
//     var key = localStorage.key(i);
//     if (key.startsWith('pedido_')) {
//         var id_pedido = key.substring(7);
//         var status_pedido = localStorage.getItem(key);

//         if (status_pedido === 'true') {
//             var pedidoElement = document.querySelector('.aguardando_confirmacao[id_pedido="' + id_pedido + '"]');

//             if (pedidoElement) {
//                 pedidoElement.classList.add('blink'); // Adicione a classe de animação

//                 pedidoElement.addEventListener('mouseover', function() {
//                     this.classList.remove('blink');
//                     var pedidoId = this.getAttribute('id_pedido');

//                 });
//             }
//         }
//     }
// }
function removerClasseBlink(id_pedido) {

    $(`[id_pedido="${id_pedido}"]`).removeClass("blink");
}

function AdicionarClasseBlink(id_pedido) {

    $(`[id_pedido="${id_pedido}"]`).addClass("blink");
}

$('.atualizar_pedidos').click(function () {

    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        const value = localStorage.getItem(key);

        if (value === 'true') {
            playSound("audio/alert.mp3");
        }
    }

    var id_pedido = localStorage.getItem("id_pedido");
    $.ajax({
        type: 'GET',
        data: "consultar_pedido=inicial",
        url: "view/delivery/pedido/consultar_pedido.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result)
                + $("#id_pedido" + id_pedido).trigger('click'); // clicar automaticamente para realizar a consulta

        },
    });
})



$(window).on("beforeunload", function () {
    // Remove o valor do id_pedido do localStorage
    localStorage.removeItem("id_pedido");
});


$(".aceitar_pedido").click(function () {
    var status = '2'
    var pedido_id = $(this).attr("pedido_id")
    var tempo_entrega = $("#entrega_" + pedido_id).val()

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja aceitar esse pedido?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update(pedido_id, status, tempo_entrega)
        }
    })

})

$(".recusar_pedido").click(function () {
    var status = '7'//id recusar pedido
    var pedido_id = $(this).attr("pedido_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja recusar esse pedido?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update(pedido_id, status, "")
        }
    })

})
/*aceitar cancelamento feito pelo cliente */
$(".aceitar_cancelamento").click(function () {
    var status = '8'
    var pedido_id = $(this).attr("pedido_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja aceitar esse cancelamento?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update(pedido_id, status, "")
        }
    })

})
/*recusar cancelamento feito pelo cliente */
$(".recusar_cancelamento").click(function () {
    var status = 'rec_can'
    var pedido_id = $(this).attr("pedido_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja recusar esse cancelamento?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update(pedido_id, status, "")
        }
    })

})

function update(pedido_id, status, tempo_entrega) {

    $.ajax({
        type: "POST",
        data: "consultar_pedido=true&acao=update_status&status=" + status + "&pedido_id=" + pedido_id + "&tempo_entrega=" + tempo_entrega,
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
            localStorage.setItem('pedido_' + pedido_id + '', JSON.stringify(false)); // Define o valor para false

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





// $("#pesquisar_filtro_pesquisa").click(function () {
//     if (conteudo_pesquisa.value == "" && status_prod.value == "0") {
//         $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
//         setTimeout(function () {
//             $(".alerta .alert").css("display", "none")
//         }, 5000);
//     } else {
//         $.ajax({
//             type: 'GET',
//             data: "consultar_produto=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + "&status_prod=" + status_prod.value + "&tipo_produto=" + tipo_produto.value,
//             url: "view/estoque/produto/table/consultar_produto.php",
//             success: function (result) {
//                 return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
//             },
//         });
//     }
// })


// $("#adicionar_produto").click(function () {
//     /*abrir modal */
//     $.ajax({
//         type: 'GET',
//         data: "cadastro_produto=true",
//         url: "view/estoque/produto/produto_tela.php",
//         success: function (result) {
//             return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_produto").modal('show');;

//         },
//     });
// })
