$(".enviar_cancelamento_nf").click(function () {
    var justificativa = document.getElementById('justificativa_cancelamento_nf')
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa nota?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            if (justificativa.value == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: "Informe a justificativa para prosseguir",
                    timer: 7500,

                })
            } else {
                cancelar_nf(id_nf_fiscal.value, justificativa.value)
            }
        }
    })
})

function cancelar_nf(id, justificativa) {
    $(".modal-body #carregando").css("display", "block")

    $.ajax({
        type: "POST",
        data: "nf_saida=true&acao=fiscal&subacao=cancelar_nf&nf_id=" + id + "&justificativa=" + justificativa,
        url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#status_processamento").val($dados.valores)
            $(".modal-body #carregando").css("display", "none")
            if ($dados.opem_danfe_nf != "") {
          
                window.open($dados.opem_danfe_nf);//abrir o pdf da carta de correção
            }

            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.mensagem_sefaz,
                showConfirmButton: false,
                timer: 3500
            })

        } else {
            $("#status_processamento").val($dados.valores)
            $(".modal-body #carregando").css("display", "none")

            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.mensagem_sefaz,
                timer: 7500,
            })
        }
    }

    function falha() {
        console.log("erro");
    }

}