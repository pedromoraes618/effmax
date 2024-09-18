
const formulario_item = document.getElementById("form_produto")
var item_id = $("#item_id").val()
var codigo_nf_item = $("#codigo_nf").val()


//modal para consultar o produto
$("#buscar_item").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_produto=true&operacao_produto=compra",
        url: "view/include/produto/pesquisa_produto.php",
        success: function (result) {
            return $(".modal_externo_externo").html(result) + $("#modal_pesquisa_produto").modal('show');

        },
    });
});

if (item_id != "") {
    show(item_id) // funcao para retornar os dados para o formulario
}

$('#form_produto').submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (item_id != "") {//update
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse Item",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                updateItem(formulario, codigo_nf_item)
            }
        })
    } else {//INSERT
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja Incluir esse Item",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {

                insertItem(formulario, codigo_nf_item)
            }
        })
    }
})


$('#cadastrar_item').click(function () {


    var dataToSend = {
        codigo_nf: $("#codigo_nf").val(),
        item_id: $("#item_id").val(),
        descricao_produto: $("#descricao_produto").val(),
        unidade: $("#unidade").val(),
        quantidade: $("#quantidade").val(),
        referencia_prod: $("#referencia_prod").val(),
        preco_compra_unitario: $("#preco_compra_unitario").val(),
        valor_total_compra: $("#valor_total_compra").val(),
        cfop_prod: $("#cfop_prod").val(),
        ncm_prod: $("#ncm_prod").val(),
        cest_prod: $("#cest_prod").val(),
        cst_icms_prod: $("#cst_icms_prod").val(),
        base_icms_prod: $("#base_icms_prod").val(),
        aliq_icms_prod: $("#aliq_icms_prod").val(),
        vlr_icms_prod: $("#vlr_icms_prod").val(),
        base_icms_sub_prod: $("#base_icms_sub_prod").val(),
        vlr_icms_sub_prod: $("#vlr_icms_sub_prod").val(),
        aliq_ipi_prod: $("#aliq_ipi_prod").val(),
        ipi_prod: $("#ipi_prod").val(),
        base_pis_prod: $("#base_pis_prod").val(),
        pis_prod: $("#pis_prod").val(),
        cst_pis_prod: $("#cst_pis_prod").val(),
        base_cofins_prod: $("#base_cofins_prod").val(),
        cofins_prod: $("#cofins_prod").val(),
        cst_cofins_prod: $("#cst_cofins_prod").val(),
        numero_pedido: $("#numero_pedido").val(),
        item_pedido: $("#item_pedido").val(),
        gtin: $("#gtin").val(),
        fabricante_prod: $("#fabricante_prod").val()
    };

    //INSERT
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja adicionar esse item ao seu estoque",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cadastrar_item(dataToSend, codigo_nf_item)
        }
    })

})


function insertItem(dados, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=insert_item&" + dados.serialize(),
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
            tabela_prod(codigo_nf)
            $('#fechar_modal_item').trigger('click')

            //  formulario_item.reset(); // redefine os valores do formulário

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


function cadastrar_item(dados, codigo_nf) {
    let dadosJSON = JSON.stringify(dados); //codificar para json
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=cadastrar_item&&dados=" + dadosJSON,
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
            tabela_prod(codigo_nf)
            $('#fechar_modal_item').trigger('click')

            //  formulario_item.reset(); // redefine os valores do formulário

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


function updateItem(dados, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "formulario_nf_entrada=true&acao=update_item&" + dados.serialize(),
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
            tabela_prod(codigo_nf)

            //  formulario_item.reset(); // redefine os valores do formulário

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
        data: "formulario_nf_entrada=true&acao=show_item&form_id=" + id,
        url: "modal/compra/compra_mercadoria/gerenciar_compra.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao_produto").val($dados.valores['descricao'])
            $("#produto_id").val($dados.valores['produto_id'])
            $("#unidade").val($dados.valores['unidade'])

            $("#quantidade").val($dados.valores['quantidade'])
            $("#preco_compra_unitario").val($dados.valores['preco_compra_unitario'])
            $("#valor_total_compra").val($dados.valores['valor_total_compra'])
            $("#cfop_prod").val($dados.valores['cfop_prod'])
            $("#ncm_prod").val($dados.valores['ncm_prod'])
            $("#cest_prod").val($dados.valores['cest_prod'])
            $("#cst_icms_prod").val($dados.valores['cst_icms_prod'])
            $("#base_icms_prod").val($dados.valores['base_icms_prod'])
            $("#aliq_icms_prod").val($dados.valores['aliq_icms_prod'])
            $("#vlr_icms_prod").val($dados.valores['vlr_icms_prod'])
            $("#base_icms_sub_prod").val($dados.valores['base_icms_sub_prod'])
            $("#vlr_icms_sub_prod").val($dados.valores['vlr_icms_sub_prod'])
            $("#aliq_ipi_prod").val($dados.valores['aliq_ipi_prod'])
            $("#ipi_prod").val($dados.valores['ipi_prod'])
            $("#base_pis_prod").val($dados.valores['base_pis_prod'])
            $("#pis_prod").val($dados.valores['pis_prod'])
            $("#cst_pis_prod").val($dados.valores['cst_pis_prod'])
            $("#base_cofins_prod").val($dados.valores['base_cofins_prod'])
            $("#cofins_prod").val($dados.valores['cofins_prod'])
            $("#cst_cofins_prod").val($dados.valores['cst_cofins_prod'])
            $("#numero_pedido").val($dados.valores['numero_pedido'])
            $("#item_pedido").val($dados.valores['item_pedido'])
            $("#gtin").val($dados.valores['gtin'])
            $("#referencia_prod").val($dados.valores['referencia_prod'])
            $("#fabricante_prod").val($dados.valores['fabricante_prod'])

            if ($dados.valores['produto_id'] == "") {
                // Crie o elemento do botão
                var botao = $("<button type='button' id='cadastrar_item' class='btn btn-sm btn-info'>Cadastrar Item</button>");

                // Adicione o botão à div
                $(".group-btn").prepend(botao);
            }
        }
    }

    function falha() {
        console.log("erro");
    }

}



function calculaValorTotalProduto() {

    var qtd_produto = parseFloat($("#quantidade").val()) || 0;
    var valor_unitario = parseFloat($("#preco_compra_unitario").val()) || 0;
    var valorTotal = qtd_produto * valor_unitario;

    $("#valor_total_compra").val(valorTotal.toFixed(2));
}



// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });


$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_produto'), // Para garantir que funcione no modal
        width: '100%',

    });
});

