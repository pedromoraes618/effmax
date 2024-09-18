
const formulario_post_servico = document.getElementById("servico_os");
const formulario_post_peca = document.getElementById("peca_os");
var form_id = document.getElementById("id")
var item_id_material = document.getElementById("item_id_material")
show(form_id.value) // funcao para retornar os dados para o formulario

$(".btn-fechar").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
})


//formulario servico
$("#servico_os").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (form_id.value != "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja confirmar o diagnóstico? ",
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


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=update_diagnostico_tecnico&" + dados.serialize(),
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
            $(".numero_ordem").val($dados.valores['numero_nf']);

            // $("#data_abertura").val($dados.valores['data_abertura']);
            // $("#parceiro_id").val($dados.valores['parceiro_id']);
            // $("#parceiro_descricao").val($dados.valores['parceiro_descricao']);
            // $("#contato").val($dados.valores['contato']);
            // $("#forma_pagamento").val($dados.valores['forma_pagamento_id']);
            $("#valor_servico").val($dados.valores['valor_servico']);
            $("#tipo_servico").val($dados.valores['tipo_servico_id']);
            $("#status").val($dados.valores['status_id']);
            $("#numero_caixa").val($dados.valores['numero_caixa']);
            $("#numero_serie").val($dados.valores['numero_serie']);
            $("#equipamento").val($dados.valores['equipamento']);
            // $("#ordem_fechada").prop('checked', $dados.valores['ordem_fechada'] === true);
            //$("#pedido_pecas").prop('checked', $dados.valores['solicitacao_pecas'] === true);
            $("#defeito_informado").val($dados.valores['defeito_informado']);
            $("#defeito_constatado").val($dados.valores['defeito_constatado']);
            // $("#observacao").val($dados.valores['observacao']);
            // $("#nf_garantia_fabrica").val($dados.valores['nf_garantia_fabrica']);
            // $("#data_garantia_fabrica").val($dados.valores['data_nf_garantia_fabrica']);
            // $("#nf_garantia_loja").val($dados.valores['nf_garantia_loja']);
            // $("#data_garantia_loja").val($dados.valores['data_nf_garantia_loja']);
            // $("#validade_garantia_loja").val($dados.valores['validade_garantia_loja']);

        }
    }

    function falha() {
        console.log("erro");
    }

}


/*material */
consultar_tabela_material_ordem_servico(form_id.value)

$("#modal_produto").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_produto=true",
        url: "view/include/produto/pesquisa_produto.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_produto").modal('show');

        },
    });
});

/*formulario servico */
$("#peca_os").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (item_id_material.value == '') {//adicioanr material
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja incluir esse material?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                peca_ordem_servico(formulario, form_id.value)
            }
        })
    } else {//alterar material
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse material?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                peca_ordem_servico(formulario, form_id.value)
            }
        })
    }
})

function peca_ordem_servico(dados, id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=pecas_diagnostico_tecnico&form_id=" + id + "&" + dados.serialize(),
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
            if ($dados.query == 'update') {
                $("#button_form_peca").html("Incluir")
                $("#modal_produto").css('display', 'block')
            }

            formulario_post_peca.reset(); // redefine os valores do formulário
            $('#item_id_material').val('')
            consultar_tabela_material_ordem_servico(id)//atualiza a tabela de materiais da os

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

function consultar_tabela_material_ordem_servico(id) {

    $.ajax({
        type: 'GET',
        data: "material_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_materiais_ordem_servico.php",
        success: function (result) {
            return $(".tabela_material").html(result);

        },
    });
}


//calulcar o valor total do item
function calcular_valor_total() {
    var valor_unitario = $('#preco_venda').val();
    var quantidade = $('#quantidade').val();

    if (valor_unitario.includes(",")) {
        valor_unitario = valor_unitario.replace(",", ".");
    }
    valor_unitario = parseFloat(valor_unitario)

    if (quantidade.includes(",")) {
        quantidade = quantidade.replace(",", ".");
    }
    quantidade = parseFloat(quantidade)

    valor_total = (valor_unitario * quantidade).toFixed(2);
    $('#valor_total').val(valor_total);
}





/*adicionar/alterar item serviço */
const formulario_item_servico_post = document.getElementById("item_servico");
consultar_tabela_servico_ordem_servico(form_id.value)
var item_id_servico = document.getElementById("item_id_servico")

// if (item_id_servico.value == "") {
//     // $(".title .sub-title").html("Incluir Servico")
//     $("#button_form_servico").html("Adicionar")
// } else {
//     // $(".title .sub-title").html("Alterar Servico")
//     $("#button_form_servico").html("Alterar")
// }


$("#modal_servico").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_servico=true",
        url: "view/include/servico/pesquisa_servico.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_servico").modal('show');

        },
    });
});


$("#item_servico").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (item_id_servico.value == '') {//adicioanr material
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja incluir esse serviço?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                servico_ordem_servico(formulario, form_id.value)
            }
        })
    } else {//alterar material
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse serviço?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                servico_ordem_servico(formulario, form_id.value)
            }
        })
    }
})

function servico_ordem_servico(dados, id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=servico&form_id=" + id + "&" + dados.serialize(),
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
            if ($dados.query == 'update') {
                $(".title .sub-title-material").html("Incluir Servico")
                // $("#button_form_peca").html("Incluir")
                $("#modal_produto").css('display', 'block')
            }

            formulario_item_servico_post.reset(); // redefine os valores do formulário
            $('#item_id_servico').val('')
            consultar_tabela_servico_ordem_servico(id)//atualiza a tabela de materiais da os
            show_valores(id)//atualiza os valores na tela de resumo de valores


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


function consultar_tabela_servico_ordem_servico(id) {

    $.ajax({
        type: 'GET',
        data: "servico_1_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_servicos_ordem_servico.php",
        success: function (result) {
            return $(".tabela_servicos").html(result);

        },
    });
}

//calulcar o valor total do item
function calcular_valor_total_servico_item() {
    var valor_unitario = $('#valor_unitario_servico_item').val();
    var quantidade = $('#quantidade_servico_item').val();

    if (valor_unitario.includes(",")) {
        valor_unitario = valor_unitario.replace(",", ".");
    }
    valor_unitario = parseFloat(valor_unitario)

    if (quantidade.includes(",")) {
        quantidade = quantidade.replace(",", ".");
    }
    quantidade = parseFloat(quantidade)

    valor_total = (valor_unitario * quantidade).toFixed(2);
    $('#valor_total_servico_item').val(valor_total);
}



$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_diagnostico_tecnico'), // Para garantir que funcione no modal
        width: '100%',

    });
});
