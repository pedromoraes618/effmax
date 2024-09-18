
// enviar_nf(id_nf_fiscal.value)
// Inicializar o intervalo e armazenar a referência para poder parar mais tarde
var id_nf_fiscal = $("#nf_id").val()
var intervaloConsulta = setInterval(function () {
    consultar_status_nf(id_nf_fiscal);
}, 3000);



$(".modal-body #carregando").css("display", "block")
$.ajax({
    type: "POST",
    data: "nf_saida=true&acao=fiscal&subacao=enviar_nf&nf_id=" + id_nf_fiscal,
    url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
    async: false
}).then(sucesso, falha);

function sucesso(data) {

    $dados = $.parseJSON(data)["dados"];
    if ($dados.sucesso == true) {
        $("#status_processamento").val($dados.valores)
    } else {
        $("#status_processamento").val($dados.valores)
        $(".modal-body #carregando").css("display", "none")
        clearInterval(intervaloConsulta);
        // consultar_status_nf(id_nf_fiscal.value)

    }
}

function falha() {
    console.log("erro");
}



$(".consultar_status_nf").click(function () {
    consultar_status_nf(id_nf_fiscal)
})




function consultar_status_nf(id) {

    $.ajax({
        type: "POST",
        data: "nf_saida=true&acao=fiscal&subacao=consultar_nf&nf_id=" + id,
        url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#status_processamento").val($dados.valores)
            if ($dados.status == "autorizado") {
                $("#status_processamento").val($dados.valores)
                $("#chave_acesso").val($dados.chave_acesso)
                $("#protocolo").val($dados.nprotocolo)
                window.open($dados.opem_danfe_nf);//abrir o pdf da nota
                clearInterval(intervaloConsulta);
                $(".modal-body #carregando").css("display", "none")

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: $dados.mensagem_sefaz,
                    showConfirmButton: false,
                    timer: 3500
                })

            }
        } else {
            if ($dados.status == "erro_autorizacao") {
                $("#chave_acesso").val($dados.chave_acesso)
                $("#status_processamento").val($dados.valores)
                clearInterval(intervaloConsulta);
                $(".modal-body #carregando").css("display", "none")

                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.mensagem_sefaz,
                    timer: 7500,
                })

            } else if ($dados.status == "erro_schema") {
                $("#status_processamento").val($dados.valores)
                clearInterval(intervaloConsulta);
                $(".modal-body #carregando").css("display", "none")
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.mensagem_sefaz,
                    timer: 7500,
                })

            } else {//ambiente instavel , fora do ar ou é um erro ainda não descoberto
                $("#status_processamento").val($dados.mensagem_sefaz + $dados.valores)

                // Swal.fire({
                //     icon: 'error',
                //     title: 'Verifique!',
                //     text: $dados.mensagem_sefaz,
                //     showConfirmButton: false,
                //     timer: 3500
                // })

            }

        }
    }

    function falha() {
        console.log("erro");
    }

}