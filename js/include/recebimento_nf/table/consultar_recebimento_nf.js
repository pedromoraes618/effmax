//abrir a pagina de edição do formulario, pegando o id 
$(".cancelar_lancamento").click(function () {
    var lcf_id = $(this).attr("id")
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar esse lançamento?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelar_lancamento(lcf_id, false)
        }
    })
})


function cancelar_lancamento(lcf_id, check_autorizador) {

    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=cancelar_lancamento_nf&id=" + lcf_id + "&check_autorizador=" + check_autorizador,
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
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
                $('#fechar_modal_recebimento').trigger('click'); // clicar automaticamente para fechar o modal
                //  $(".receber_nf").remove()
            }, 1000);
        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&acao=autorizar_cancelamento_financeiro&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo_2").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_cancelamento_financeiro_recebimento_nf")
                        + $("#autorizar_acao").attr("data-lcf-id", lcf_id)
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


