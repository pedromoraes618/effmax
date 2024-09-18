
$("#venda_mercadoria").submit(function (e) {
    e.preventDefault()
    var id_nf = $("#id").val()
    if (id_nf != '') {
        var mensagem = "Deseja alterar essa venda?"
    } else {
        var mensagem = "Deseja finalizar essa venda?"
    }
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: mensagem,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = create(formulario, produtos, codigo_nf.value);
        }
    })
})

var id_nf = $("#id").val()
if (id_nf != "") {//editar nf
    show_det_finalizar_nf(id_nf, codigo_nf.value)
    updateTotal()
    $('#finalizar_venda').html("<i class='bi bi-check-all'></i> Alterar venda")
    $("#modal_finalizar_venda .modal-title").html('Alterar venda')
} else {
    var parceiro_id = $('#parceiro_id').val()
    show_det_preview_nf(parceiro_id)
}


//mostrar as informações no formulario show
function show_det_finalizar_nf(id, codigo_nf) {

    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=show&nf_id=" + id + "&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#sub_total_venda").val($dados.valores['sub_total_venda'])
            $("#desconto_venda_real").val($dados.valores['desconto_venda_real'])
            $("#valor_liquido_venda").val($dados.valores['valor_liquido_venda'])
            $("#vendedor_id_venda").val($dados.valores['vendedor_id_venda'])
            $("#id_forma_pagamento_venda").val($dados.valores['id_forma_pagamento_venda'])
            $('.descricao_forma_pagamento_venda').html($dados.valores['descricao_forma_pagamento_venda'])
            $("#credito_atual").val($dados.valores['credito_atual'])
            $("#valor_credito").val($dados.valores['valor_credito'])
        }
    }

    function falha() {
        console.log("erro");
    }

    }

//mostrar as informações no formulario show
function show_det_preview_nf(parceiro_id) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=show_preview_nf&parceiro_id=" + parceiro_id,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#credito_atual").val($dados.valores['credito_atual'])
        }
    }

    function falha() {
        console.log("erro");
    }

}



function updateTotal() {
    const subTotal = parseFloat($("#sub_total_venda").val()) || 0;
    const descontoReal = parseFloat($("#desconto_venda_real").val()) || 0;
    const valor_credito = parseFloat($("#valor_credito").val()) || 0;

    const descontoRealTotal = descontoReal;
    const totalComDesconto = subTotal - descontoRealTotal -valor_credito;

    $("#valor_liquido_venda").val(totalComDesconto.toFixed(2));

    const descontoPorcentagem = (descontoRealTotal / subTotal) * 100 || 0;
    $("#desconto_venda_porcentagem").val(descontoPorcentagem.toFixed(2));
}

// //calulcar o valor liquido do produto
// function calcular_desconto_venda_real() {
//     var valor_bruto_Venda = $('#sub_total_venda').val();
//     var desconto_venda_real = $('#desconto_venda_real').val();
//     var valor_entrega_delivery = parseFloat($('#valor_entrega_delivery').val()) || 0;

//     if (valor_bruto_Venda != "" && desconto_venda_real != "") {//verificar se o valores foram preenchidos
//         if (desconto_venda_real) {
//             if (desconto_venda_real.includes(",")) {
//                 desconto_venda_real = desconto_venda_real.replace(",", ".");
//             }
//             if (valor_bruto_Venda.includes(",")) {
//                 valor_bruto_Venda = valor_bruto_Venda.replace(",", ".");
//             }
//             desconto_venda_real = parseFloat(desconto_venda_real)
//             valor_bruto_Venda = parseFloat(valor_bruto_Venda)
//             valor_liquido_venda = valor_entrega_delivery + valor_bruto_Venda - desconto_venda_real
//             desconto_porcentagem = ((desconto_venda_real / valor_bruto_Venda) * 100)

//             $('#desconto_venda_porcentagem').val(desconto_porcentagem.toFixed(2))

//             $('#valor_liquido_venda').val(valor_liquido_venda.toFixed(2))

//         }
//     }
// }
// //calulcar o valor liquido do produto
// function calcular_desconto_venda_porcentagem() {
//     var valor_bruto_Venda = $('#sub_total_venda').val();
//     var desconto_venda_porcentagem = $('#desconto_venda_porcentagem').val();
//     var desconto_venda_real = $('#desconto_venda_real').val();


//     if (valor_bruto_Venda != "" && desconto_venda_porcentagem != "") {//verificar se o valores foram preenchidos
//         if (desconto_venda_porcentagem) {
//             if (desconto_venda_porcentagem.includes(",")) {
//                 desconto_venda_porcentagem = desconto_venda_porcentagem.replace(",", ".");
//             }
//             if (valor_bruto_Venda.includes(",")) {
//                 valor_bruto_Venda = valor_bruto_Venda.replace(",", ".");
//             }
//             desconto_venda_porcentagem = parseFloat(desconto_venda_porcentagem)
//             valor_bruto_Venda = parseFloat(valor_bruto_Venda)

//             calcular_desconto_real = ((desconto_venda_porcentagem / 100) * valor_bruto_Venda)

//             $('#desconto_venda_real').val(calcular_desconto_real.toFixed(2))
//             $('#valor_liquido_venda').val((valor_bruto_Venda - desconto_venda_real).toFixed(2))
//         }
//     }
// }


$(".seleciona_fpg").click(function () {

    var id_fpg = $(this).attr("id_fpg")
    var descricao_fpg = $('.descricao_fpg_' + id_fpg).html()
    $('#id_forma_pagamento_venda').val(id_fpg)
    $('.descricao_forma_pagamento_venda').html(descricao_fpg)

})