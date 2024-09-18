const formulario_post = document.getElementById("pecas");
consultar_tabela_material_ordem_servico(form_id.value)

var item_id_material = document.getElementById("item_id_material")
//modal para consultar o produto

if (item_id_material.value == "") {
    $(".title .sub-title").html("Adicionar Material")
    // $("#button_form_peca").html("Adicionar")
} else {
    $(".title .sub-title").html("Alterar Material")
    // $("#button_form_peca").html("Alterar")
}

$(".requisitar_material").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja requisitar todos os materiais?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            requisitar_material(form_id.value)
        }
    })
})
$(".cancelar_requisicao_material").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar a requisição de todos os materiais?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelar_requisicao_material(form_id.value)
        }
    })
})
$("#modal_produto").click(function () {
    var tipo_material_id = $("#tipo_material").val()
    var span_tipo
    if (tipo_material_id == 10) {
        span_tipo = "&tipo_produto=10"
    }

    $.ajax({
        type: 'GET',
        data: "adicionar_produto=true" + span_tipo,
        url: "view/include/produto/pesquisa_produto.php",
        success: function (result) {
            return $(".modal_externo_3").html(result) + $("#modal_pesquisa_produto").modal('show');

        },
    });
});

$("#pecas").submit(function (e) {
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
        data: "formulario_ordem_servico=true&acao=pecas&form_id=" + id + "&" + dados.serialize(),
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
                $(".title .sub-title-material").html("Incluir Material")
                // $("#button_form_peca").html("Incluir")
                $("#modal_produto").css('display', 'block')
            }

            formulario_post.reset(); // redefine os valores do formulário
            $('#item_id_material').val('')
            consultar_tabela_material_ordem_servico(id)//atualiza a tabela de materiais da os
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

function requisitar_material(id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=requisitar_peca&form_id=" + id,
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

            consultar_tabela_material_ordem_servico(id)//atualiza a tabela de materiais da os
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

function cancelar_requisicao_material(id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=cancelar_requisicao_peca&form_id=" + id,
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

            consultar_tabela_material_ordem_servico(id)//atualiza a tabela de materiais da os
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

function consultar_tabela_material_ordem_servico(id) {//uso em geral
    $.ajax({
        type: 'GET',
        data: "material_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_materiais_ordem_servico.php",
        success: function (result) {
            return $(".tabela_material").html(result);

        },
    });
    consultar_tabela_material_ordem_servico_ativo_imobilizado(id)

}
function consultar_tabela_material_ordem_servico_ativo_imobilizado(id) {//uso em geral
    $.ajax({
        type: 'GET',
        data: "material_ordem_servico=true&form_id=" + id + "&tipo_produto_id=10",
        url: "view/servico/ordem_servico/table/consultar_materiais_ordem_servico.php",
        success: function (result) {
            return $(".tabela_material_ativo_imobilizado").html(result);

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


const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))


$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_pecas_ordem_servico'), // Para garantir que funcione no modal
        width: '100%',

    });
});