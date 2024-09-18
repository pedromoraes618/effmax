$("#variacao_opcao").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja adicionar essas opções para este produto?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = opcao_produto(formulario)
        }
    })
})



function opcao_produto(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_produto=true&acao=opcao_produto&" + dados.serialize() + "&codigo_nf=" + codigo_nf.value,
        url: "modal/estoque/produto/gerenciar_produto.php",
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
            consultar_variacao(codigo_nf.value)

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

var opcao_id = $("#opcao_id").val()
if (opcao_id != "") {
    show_opcao(opcao_id)
}



//mostrar as informações no formulario show
function show_opcao(id) {

    $.ajax({
        type: "POST",
        data: "formulario_produto=true&acao=show_opcao&codigo_nf=" + codigo_nf.value + "&opcao_id=" + id,
        url: "modal/estoque/produto/gerenciar_produto.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#nome_opcao").val($dados.valores['nome_opcao'])
            $("#variantes_opcao").val($dados.valores['variantes_opcao'])

            if (($dados.valores['incluir_titulo']) == "1") {
                $('#incluir_titulo').attr('checked', true);
            } else {
                $('#incluir_titulo').attr('checked', false);
            }

        }
    }

    function falha() {
        console.log("erro");
    }

}