
show_frete() // funcao para retornar os dados para o formulario

//mostrar as informações no formulario show
function show_frete() {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=show_frete",
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#frete_gratis").val($dados.valores['frete_gratis']);
            $("#taxa_frete_gratis_dentro_estado").val($dados.valores['taxa_frete_gratis_dentro_estado']);
            $("#taxa_frete_gratis_fora_estado").val($dados.valores['taxa_frete_gratis_fora_estado']);
            $("#valor_entrega_local").val($dados.valores['valor_entrega_local']);
            $("#prazo_entrega_local").val($dados.valores['prazo_entrega_local']);
            $("#codigo_postal_entrega_local").val($dados.valores['codigo_postal_entrega_local']);
            $("#frete_retirada").val($dados.valores['frete_retirada']);
            $("#endereco_retirada").val($dados.valores['endereco_retirada']);
            $("#instrucao_retirada").val($dados.valores['instrucao_retirada']);
            $("#qtd_postagem").val($dados.valores['qtd_postagem']);
        }
    }

    function falha() {
        console.log("erro");
    }

}
$("#form_frete").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas configurações do frete?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update_frete(formulario, "update_frete")
        }
    })
})

$("#form_caixa_modelo").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    var caixa_id = $("#caixa_id").val()


    if (caixa_id === "") {
        text = "Deseja incluir esse do modelo da caixa?"
    } else {
        text = "Deseja alterar esse do modelo da caixa?"

    }
    Swal.fire({
        title: 'Tem certeza?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update_frete(formulario, "update_modelo_caixa")
        }
    })
})



$("#form_frete_gratis").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas configurações do frete grátis?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update_frete(formulario, "update_frete_gratis")
        }
    })
})

$("#frete_local").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
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
            var retorno = update_frete(formulario, "update_frete_local")
        }
    })
})

$("#frete_retirada_local").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
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
            var retorno = update_frete(formulario, "update_frete_retirada_local")
        }
    })
})
function update_frete(dados, acao) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=" + acao + "&" + dados.serialize(),
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
            if (acao == "update_modelo_caixa") {
                $("#aba_frete").trigger('click')
            }

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


//mostrar as informações no formulario show
function show_modelo_caixa(id) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=show_modelo_caixa&id=" + id,
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            $("#caixa_id").val($dados.valores['id']);
            $("#nome_caixa").val($dados.valores['nome']);
            $("#limite_produto_caixa").val($dados.valores['limite_produto']);
            $("#largura_caixa").val($dados.valores['largura']);
            $("#comprimento_caixa").val($dados.valores['comprimento']);
            $("#altura_caixa").val($dados.valores['altura']);
            $("#peso_caixa").val($dados.valores['peso']);
        }
    }

    function falha() {
        console.log("erro");
    }

}

$(".editar_caixa").click(function () {
    var caixa_id = $(this).attr("id")
    show_modelo_caixa(caixa_id)
})

