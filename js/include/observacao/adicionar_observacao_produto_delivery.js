
// $(document).ready(function(){//ao abrir o modal se já tiver alguma observação no imput do formulario, será informado no campo observacao no modal
//    var observacao = $('#observacao').val()
//     $('#valor_observacao').val(observacao);
// })

$("#salvar_observacao_prd_delivery").click(function () {//adicionar observação no imput do formulario

    var id_item_nf_delivery = $(this).attr("id_item_nf")
    var observacao_prd_delivery = $("#valor_observacao_prd_delivery").val()

    if (id_item_nf_delivery != "") {
        adiconar_observaco_prd_delivery(id_item_nf_delivery,observacao_prd_delivery)
    }

})

function adiconar_observaco_prd_delivery(id_item_nf_delivery,conteudo) {

    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=adicionar_observacao_prd_delivery&id_item_nf=" + id_item_nf_delivery +"&observacao=" + conteudo,
        url: "modal/venda/venda_mercadoria_delivery/gerenciar_venda.php",
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
            setTimeout(function () {
                $("#fechar_modal_observacao_prd_delivery").trigger('click');//alterar a label cabeçalho
            }, 2000);


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