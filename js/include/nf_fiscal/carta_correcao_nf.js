$(".enviar_carta_correcao_nf").click(function () {
    var informativo_carta_correcao_nf = document.getElementById('informativo_carta_correcao_nf')
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja enviar uma carta de correção a essa nota?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            if (informativo_carta_correcao_nf.value == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: "Informe a correção para prosseguir",
                    timer: 7500,

                })
            } else {
                carta_correcao_nf(id_nf_fiscal.value, informativo_carta_correcao_nf.value)
            }
        }
    })
})

function carta_correcao_nf(id, informativo_carta_correcao_nf) {
    $(".modal-body #carregando").css("display", "block")

    $.ajax({
        type: "POST",
        data: "nf_saida=true&acao=fiscal&subacao=carta_correcao_nf&nf_id=" + id + "&correcao=" + informativo_carta_correcao_nf,
        url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#status_processamento").val($dados.valores)
            $(".modal-body #carregando").css("display", "none")
            window.open($dados.opem_danfe_crt);//abrir o pdf da carta de correção

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