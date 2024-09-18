
show_politicas() // funcao para retornar os dados para o formulario

//mostrar as informações no formulario show
function show_politicas() {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=show_politicas",
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            $("#termos_condicoes .ql-editor").html($dados.valores['termos_condicoes']);
            $("#politicas_privacidade .ql-editor").html($dados.valores['politicas_privacidade']);
            $("#politicas_devolucao .ql-editor").html($dados.valores['politicas_devolucao']);

        }
    }

    function falha() {
        console.log("erro");
    }
}



$("#politica").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    var termos_condicoes = $("#termos_condicoes .ql-editor").html()
    var politicas_privacidade = $("#politicas_privacidade .ql-editor").html()
    var politicas_devolucao = $("#politicas_devolucao .ql-editor").html()
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas configurações.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update_politicas(formulario, "update_politicas", termos_condicoes, politicas_privacidade, politicas_devolucao)
        }
    })
})


function update_politicas(dados, acao, termos_condicoes, politicas_privacidade, politicas_devolucao) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=" + acao + "&termos_condicoes=" + termos_condicoes
            + "&politicas_privacidade=" + politicas_privacidade + "&politicas_devolucao=" + politicas_devolucao + "&" + dados.serialize(),
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
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

