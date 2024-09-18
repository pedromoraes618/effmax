
const formulario_post = document.getElementById("ordem_servico");
let id_form = document.getElementById("id")

//retorna os dados para o formulario
if (id_form.value == "") {
    $(".title .sub-title").html("Lançar Ordem de Serviço")
} else {
    $(".title .sub-title").html("Alterar Ordem de Serviço")
    show(id_form.value) // funcao para retornar os dados para o formulario
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

$(".btn-fechar").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
})

//modal para consultar os valores da os
$(".modal_resumo_valores").click(function () {
    if (id_form.value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "É necesssario adicionar a ordem de serviço",
            timer: 7500,
        })
    } else {
        $.ajax({
            type: 'GET',
            data: "resumo_valores=true&form_id=" + id_form.value,
            url: "view/include/servico/valores_ordem_servico.php",
            success: function (result) {
                return $(".modal_externo").html(result) + $("#modal_resumo_valores_ordem_servico").modal('show');

            },
        });
    }
});


//modal para consultar os anexo
$(".modal_anexo").click(function () {
    if (id_form.value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "É necesssario adicionar a ordem de serviço",
            timer: 7500,
        })
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_anexo=true&form_id=" + id_form.value + "&tipo=ordem_servico",
            url: "view/include/anexo/consultar_anexo.php",
            success: function (result) {
                return $(".modal_externo").html(result) + $("#modal_anexo").modal('show');

            },
        });
    }
});


//formulario para cadastro
$("#cancelar").click(function () {

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa ordem de serviço?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cancelar_os(id_form.value, '', false)
        }
    })

})


//formulario para cadastro
$("#ordem_servico").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (id_form.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar essa ordem de serviço?",
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
    } else {//editar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar essa ordem de serviço? ",
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

function create(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=create&" + dados.serialize(),
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
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

            //   formulario_post.reset(); // redefine os valores do formulário
            $("#id").val($dados.id);
            $("#numero_ordem").val($dados.numero_os);
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


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=update&" + dados.serialize(),
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
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


function cancelar_os(form_id, usuario_id, check_autorizador) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=cancelar_os&form_id=" + form_id + "&usuario_id=" + usuario_id + "&check_autorizador=" + check_autorizador,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
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
                data: "autorizar_acao=true&acao=cancelar_os&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_cancelar_os")
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

$(".documento_recibo").click(function (e) {
    e.preventDefault()
    var nf_id = $("#id").val()
    var tipo = 'recibo'
    gerar_doc(tipo, nf_id)
})
$(".documento_pdf").click(function (e) {
    e.preventDefault()
    var nf_id = $("#id").val()
    var tipo = 'pdf'
    gerar_doc(tipo, nf_id)
})

$(".documento_pdf_detalhado").click(function (e) {
    e.preventDefault()
    var nf_id = $("#id").val()
    var tipo = 'pdf_detalhado_1'
    gerar_doc(tipo, nf_id)
})

function gerar_doc(tipo, form_id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=gerar_doc&tipo=" + tipo + "&form_id=" + form_id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
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

// function remover(id) {
//     $.ajax({
//         type: "POST",
//         data: "formulario_atendimento=true&acao=delete&form_id=" + id,
//         url: "modal/empresa/atendimento/gerenciar_atendimento.php",
//         async: false
//     }).then(sucesso, falha);

//     function sucesso(data) {
//         $dados = $.parseJSON(data)["dados"];
//         if ($dados.sucesso == true) {
//             Swal.fire({
//                 position: 'center',
//                 icon: 'success',
//                 title: $dados.title,
//                 showConfirmButton: false,
//                 timer: 3500
//             })
//             $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Verifique!',
//                 text: $dados.title,
//                 timer: 7500,

//             })

//         }
//     }

//     function falha() {
//         console.log("erro");
//     }

// }



//mostrar as informações no formulario show
function show(id) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=show&form_id=" + id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#numero_ordem").val($dados.valores['serie_nf'] + $dados.valores['numero_nf']);
            $("#atendente").val($dados.valores['atendente']);
            $("#data_abertura").val($dados.valores['data_abertura']);
            $("#parceiro_id").val($dados.valores['parceiro_id']);
            $("#parceiro_descricao").val($dados.valores['parceiro_descricao']);
            $("#contato").val($dados.valores['contato']);
            $("#forma_pagamento").val($dados.valores['forma_pagamento_id']);
            $("#tipo_servico").val($dados.valores['tipo_servico_id']);
            $("#status").val($dados.valores['status_id']);
            $("#numero_caixa").val($dados.valores['numero_caixa']);
            $("#numero_serie").val($dados.valores['numero_serie']);
            $("#equipamento").val($dados.valores['equipamento']);
            // $("#ordem_fechada").prop('checked', $dados.valores['ordem_fechada'] === true);
            //$("#pedido_pecas").prop('checked', $dados.valores['solicitacao_pecas'] === true);
            $("#defeito_informado").val($dados.valores['defeito_informado']);
            $("#defeito_constatado").val($dados.valores['defeito_constatado']);
            $("#observacao").val($dados.valores['observacao']);
            $("#nf_garantia_fabrica").val($dados.valores['nf_garantia_fabrica']);
            $("#data_garantia_fabrica").val($dados.valores['data_nf_garantia_fabrica']);
            $("#nf_garantia_loja").val($dados.valores['nf_garantia_loja']);
            $("#data_garantia_loja").val($dados.valores['data_nf_garantia_loja']);
            $("#validade_garantia_loja").val($dados.valores['validade_garantia_loja']);


            $("#descricao_obra").val($dados.valores['descricao_obra']);
            $("#local_obra").val($dados.valores['local_obra']);
            $("#prazo_entrega").val($dados.valores['data_prazo_entrega']);
            $("#entrega_obra").val($dados.valores['data_entrega_obra']);
            $("#valor_fechado").val($dados.valores['valor_fechado']);

            if (($dados.valores['ordem_fechada']) == true) {
                $('#ordem_fechada').attr('checked', true);
            }
            if (($dados.valores['solicitacao_pecas']) == true) {
                $('#pedido_pecas').attr('checked', true);
            }
        }
    }

    function falha() {
        console.log("erro");
    }

}




// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });


$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_ordem_servico'), // Para garantir que funcione no modal
        width: '100%',


    });

});


