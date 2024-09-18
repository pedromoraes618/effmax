
$(".modal-body #carregando").css("display", "block")
$.ajax({
    type: "POST",
    data: "nf_saida=true&acao=fiscal&subacao=preview_nf&nf_id=" + id_nf_fiscal.value,
    url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
    async: false
}).then(sucesso, falha);

function sucesso(data) {

    $dados = $.parseJSON(data)["dados"];
    if ($dados.sucesso == true) {
        
        window.open($dados.open_danfe_nf);//abrir o pdf da nota
        $("#status_processamento").val($dados.valores)
        $(".modal-body #carregando").css("display", "none")

    } else {
        $("#status_processamento").val($dados.valores)
        $(".modal-body #carregando").css("display", "none")
        //   clearInterval(intervaloConsulta);
    }
}

function falha() {
    console.log("erro");
}

