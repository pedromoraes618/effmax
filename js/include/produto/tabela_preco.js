

// //retorna os dados para o formulario
// if (id_forma_pagamento.value == "") {
//     $('#button_form').html('Cadastrar');
//     $('#ativo').attr('checked', true);
//     $(".title .sub-title").html("Cadastrar forma pagamento")
// } else {
//     $('#button_form').html('Alterar');
//     $(".title .sub-title").html("Editar forma pagamento")
//     show(id_forma_pagamento.value) // funcao para retornar os dados para o formulario
// }

//formulario para cadastro
$("#tabela_preco").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa tabela de preço?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update_tabela_preco(formulario)
        }
    })
})


function update_tabela_preco(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_tabela_preco=true&acao=update&" + dados.serialize(),
        url: "modal/estoque/tabela_preco/gerenciar_tabela_preco.php",
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
