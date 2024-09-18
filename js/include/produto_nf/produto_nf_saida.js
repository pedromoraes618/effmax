
const formulario_item = document.getElementById("form_produto")
var item_id = $("#item_id").val()
var codigo_nf_item = $("#codigo_nf").val()


//modal para consultar o produto
$("#buscar_item").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_produto=true",
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
    }

})







function updateItem(dados, codigo_nf) {

    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=update_item_nf&" + dados.serialize(),
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
            show_valores(codigo_nf)
            tabela_produtos_nf(codigo_nf)

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
        data: "venda_mercadoria=true&acao=show_det_produto&produto_id=" + id,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao_produto").val($dados.valores['descricao'])
            $("#codigo").val($dados.valores['id_produto'])
            $("#unidade").val($dados.valores['unidade'])

            $("#quantidade").val($dados.valores['quantidade'])
            $("#preco_venda_unitario").val($dados.valores['preco_venda'])
            $("#preco_venda_total").val($dados.valores['valor_total'])
            $("#cfop_prod").val($dados.valores['cfop'])
            $("#ncm_prod").val($dados.valores['ncm'])
            $("#cest_prod").val($dados.valores['cest'])
            $("#cst_icms_prod").val($dados.valores['cst'])
            $("#base_icms_prod").val($dados.valores['base_icms'])
            $("#aliq_icms_prod").val($dados.valores['aliq_icms'])
            $("#vlr_icms_prod").val($dados.valores['icms'])
            $("#base_icms_sub_prod").val($dados.valores['base_icms_sbt'])
            $("#vlr_icms_sub_prod").val($dados.valores['icms_sbt'])
            $("#aliq_ipi_prod").val($dados.valores['aliq_ipi'])
            $("#ipi_prod").val($dados.valores['ipi'])
            $("#base_pis_prod").val($dados.valores['base_pis'])
            $("#pis_prod").val($dados.valores['pis'])
            $("#cst_pis_prod").val($dados.valores['cst_pis'])
            $("#base_cofins_prod").val($dados.valores['base_cofins'])
            $("#cofins_prod").val($dados.valores['cofins'])
            $("#cst_cofins_prod").val($dados.valores['cst_cofins'])
            $("#numero_pedido_prod").val($dados.valores['numero_pedido'])
            $("#item_pedido_prod").val($dados.valores['item_pedido'])
            $("#gtin").val($dados.valores['gtin'])
            $("#referencia_prod").val($dados.valores['referencia'])
            $("#ipi_devolvido_prod").val($dados.valores['ipi_devolvido'])
            $("#atividade_id").val($dados.valores['atividade_id'])


        }
    }

    function falha() {
        console.log("erro");
    }

}



function calculaValorTotalProduto() {

    var qtd_produto = parseFloat($("#quantidade").val()) || 0;
    var valor_unitario = parseFloat($("#preco_venda_unitario").val()) || 0;
    var valorTotal = qtd_produto * valor_unitario;

    $("#preco_venda_total").val(valorTotal.toFixed(2));
}

// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });
// $('#cfop_prod').select2({
//     dropdownParent: $('#modal_produto')
// });


// $('.select2').select2({
//     theme: 'bootstrap-5',
//     dropdownParent: $('#modal_produto'),

// })

$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_produto'), // Para garantir que funcione no modal
        width: '100%',

    });
});

