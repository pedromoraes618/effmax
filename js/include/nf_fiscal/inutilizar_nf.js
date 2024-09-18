$(".enviar_inutilizacao_nf").click(function () {
    var justificativa = document.getElementById('justificativa_inutilizacao_nf')
    var numero_nf_inutilizado = document.getElementById('numero_nf_inutilizado')
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja Inutilizar essa númeração?",
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
                inutilizar_nf(id_nf_fiscal.value, numero_nf_inutilizado.value, justificativa.value)
            }
        }
    })
})

function inutilizar_nf(id, numero_nf, justificativa) {
    $(".modal-body #carregando").css("display", "block")

    $.ajax({
        type: "POST",
        data: "nf_saida=true&acao=fiscal&subacao=inutilizar_nf&nf_id=" + id + '&numero_nf=' + numero_nf + "&justificativa=" + justificativa,
        url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#status_processamento").val($dados.valores)
            $(".modal-body #carregando").css("display", "none")
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.mensagem_sefaz,
                showConfirmButton: false,
                timer: 3500
            })

        } else if ($dados.status == "erro_autorizacao") {
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