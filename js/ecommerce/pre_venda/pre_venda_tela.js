const formulario_post = document.getElementById("pre_venda");
let form_id = document.getElementById("id")


//retorna os dados para o formulario
if (form_id.value != "") {
    show(form_id.value) // funcao para retornar os dados para o formulario
}

$(".btn-fechar").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
})




$("#pre_venda").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);


    //e.preventDefault()
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse pedido? Atenção: Dados sensíveis de clientes.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update(formulario)
        }
    })

})
$(".gerar_venda").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja gerar uma venda do pedido " + form_id.value,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = gerar_venda(form_id.value)
        }
    })

})


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_ecommerce=true&acao=update&" + dados.serialize(),
        url: "modal/ecommerce/pre_venda/gerenciar_pre_venda.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
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


function gerar_venda(id) {
    $.ajax({
        type: "POST",
        data: "formulario_ecommerce=true&acao=gerar_venda&id=" + id,
        url: "modal/ecommerce/pre_venda/gerenciar_pre_venda.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
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
function show(id) {
    $.ajax({
        type: "POST",
        data: "formulario_ecommerce=true&acao=show&form_id=" + id,
        url: "modal/ecommerce/pre_venda/gerenciar_pre_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            $("#nome").html($dados.valores['nome']);
            $("#email").html($dados.valores['email']);
            $("#telefone").html($dados.valores['telefone']);
            $("#cpfcnpj").html($dados.valores['cpfcnpj']);
            $("#telefone").html($dados.valores['telefone']);
            $(".title .sub-title").html("Pedido #" + $dados.valores['pedido']);
            $("#help_codigo_rastreio").html($dados.valores['msgRastreio'])

            $("#data_pedido").val($dados.valores['data_pedido']);
            $("#status_pagamento_tela").val($dados.valores['status_pagamento']);
            $("#status_pedido_tela").val($dados.valores['status_compra']);
            $("#forma_pagamento_id").val($dados.valores['forma_pagamento_id']);

            $("#tipo_frete").val($dados.valores['transportadora']);
            $("#codigo_rastreio").val($dados.valores['codigo_rastreio']);
            $("#observacao").val($dados.valores['observacao']);

            $("#cep").val($dados.valores['cep']);
            $("#cidade").val($dados.valores['cidade']);
            $("#estado").val($dados.valores['estado']);
            $("#endereco").val($dados.valores['endereco']);
            $("#bairro").val($dados.valores['bairro']);
            $("#complemento").val($dados.valores['complemento']);
            $("#numero").val($dados.valores['numero']);
            $("#data_entrega").val($dados.valores['data_entrega']);

        }
    }

    function falha() {
        console.log("erro");
    }

}

$(document).ready(function () {
    $('#telefone').inputmask('(99) 99999-9999'); // Defina a máscara desejada para o telefone
    $('#cep').inputmask('99999-999'); // Defina a máscara desejada para o telefone
    $('#cpf').inputmask('999.999.999-99');

});