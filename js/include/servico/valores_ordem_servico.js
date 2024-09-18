var form_id = document.getElementById("id")
show_valores(form_id.value)

//mostrar as informações no formulario show
function show_valores(id) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=show&form_id=" + id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#valor_pecas").val($dados.valores['valor_pecas_moeda']);
            $("#valor_servico").val($dados.valores['valor_servico_moeda']);
            $("#taxa").val($dados.valores['taxa_adiantamento_moeda']);
            $("#desconto").val($dados.valores['valor_desconto']);
            $("#valor_a_receber").val($dados.valores['valor_a_receber']);
            $("#valor_liquido").val($dados.valores['valor_liquido_moeda']);
            $("#valor_despesa").val($dados.valores['valor_despesa_moeda']);
        }
    }

    function falha() {
        console.log("erro");
    }
}

$("#adicionar_desconto").click(function () {
    var desconto = $("#desconto").val();
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar o desconto dessa ordem de serviço?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            adicionar_desconto(desconto, form_id.value, false)
        }
    })
})

$(".adicionar_taxa").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_taxa=true&form_id=" + form_id.value,
        url: "view/include/servico/adicionar_taxa.php",
        success: function (result) {
            return $(".modal_externo_2").html(result) + $("#modal_taxa_ordem_servico").modal('show');

        },
    });
})

$(".adicionar_despesa").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_despesa=true&form_id=" + form_id.value,
        url: "view/include/servico/adicionar_despesa.php",
        success: function (result) {
            return $(".modal_externo_2").html(result) + $("#modal_despesa_ordem_servico").modal('show');

        },
    });
})
$(".adicionar_pecas").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_pecas=true&form_id=" + form_id.value,
        url: "view/include/servico/adicionar_pecas.php",
        success: function (result) {
            return $(".modal_externo_2").html(result) + $("#modal_pecas_ordem_servico").modal('show');

        },
    });
})
$(".consultar_pecas").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_pecas=true&form_id=" + form_id.value,
        url: "view/include/servico/pecas_aplicadas.php",
        success: function (result) {
            return $(".modal_externo_2").html(result) + $("#modal_pecas_ordem_servico").modal('show');

        },
    });
})
$(".adicionar_servico").click(function () {

    $.ajax({
        type: 'GET',
        data: "adicionar_servicos=true&form_id=" + form_id.value,
        url: "view/include/servico/adicionar_servicos.php",
        success: function (result) {
            return $(".modal_externo_2").html(result) + $("#modal_servicos_ordem_servico").modal('show');

        },
    });
})

$(".faturar_os").click(function () {
    $.ajax({
        type: 'GET',
        data: "faturar_os=true&form_id=" + form_id.value,
        url: "view/include/servico/faturar_ordem_servico.php",
        success: function (result) {
            return $(".modal_externo_2").html(result) + $("#modal_faturar_ordem_servico").modal('show');

        },
    });
})


function adicionar_desconto(desconto, id, check_autorizador) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=adicionar_desconto&desconto=" + desconto + "&form_id=" + id + "&check_autorizador=" + check_autorizador,
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
            show_valores(id)
        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo_2").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_desconto_incluir_desconto_ordem_servico");
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
$("#remover_faturamento_os").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover essa ordem de serviço do faturamento?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            remover_nf_faturamento(form_id.value, '', false)
        }
    })
})

function remover_nf_faturamento(form_id, usuario_id, autorizacao) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=remover_nf_faturamento&form_id=" + form_id + "&usuario_id=" + usuario_id + "&check_autorizador=" + autorizacao,
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
                timer: 5500
            })
            $(".btn-close-valores").trigger('click')
            // $("#remover_nf_faturamento").remove(); // Remove o próprio botão
            //   tabela_produtos(codigo_nf);//recarregar a tabela de produtos
        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&acao=autorizar_remover_faturamento_nf&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo_2").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_remover_faturamento_nf_os")
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
