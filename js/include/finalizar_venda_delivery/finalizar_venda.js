
$("#venda_mercadoria").submit(function (e) {
    e.preventDefault();
    var id_nf = $("#id").val();
    var mensagem = id_nf ? "Deseja alterar essa venda?" : "Deseja finalizar essa venda?";

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
            var retorno = create($(this), produtos, codigo_nf.value);
        }
    });
});

$(".seleciona_fpg").click(function () {
    var id_fpg = $(this).attr("id_fpg");
    var descricao_fpg = $('.descricao_fpg_' + id_fpg).html();
    $('#id_forma_pagamento_venda').val(id_fpg);
    $('.descricao_forma_pagamento_venda').html(descricao_fpg);
});

var id_nf = $("#id").val();
if (id_nf) {
    showDetalhesAlteracao(id_nf, codigo_nf.value);
    updateTotal();
    $('#finalizar_venda').html("Alterar venda");
    $("#modal_finalizar_venda .modal-title").html('Alterar venda');
}

function showDetalhesAlteracao(id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=show&nf_id=" + id + "&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria_delivery/gerenciar_venda.php",
        async: false
    }).done(function (data) {
        var $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#sub_total_venda").val($dados.valores['sub_total_venda']);
            $("#desconto_venda_real").val($dados.valores['desconto_venda_real']);
            $("#valor_entrega_delivery").val($dados.valores['valor_entrega_delivery']);
            $("#valor_liquido_venda").val($dados.valores['valor_liquido_venda']);
            $("#vendedor_id_venda").val($dados.valores['vendedor_id_venda']);
            $("#id_forma_pagamento_venda").val($dados.valores['id_forma_pagamento_venda']);
            $('.descricao_forma_pagamento_venda').html($dados.valores['descricao_forma_pagamento_venda']);
        }
    }).fail(function () {
        console.log("erro");
    });
}

function updateTotal() {
    const subTotal = parseFloat($("#sub_total_venda").val()) || 0;
    const valorEntrega = parseFloat($("#valor_entrega_delivery").val()) || 0;
    const descontoReal = parseFloat($("#desconto_venda_real").val()) || 0;

    const descontoRealTotal = descontoReal;
    const totalComDesconto = subTotal + valorEntrega - descontoRealTotal;

    $("#valor_liquido_venda").val(totalComDesconto.toFixed(2));

    const descontoPorcentagem = (descontoRealTotal / subTotal) * 100 || 0;
    $("#desconto_venda_porcentagem").val(descontoPorcentagem.toFixed(2));
}
