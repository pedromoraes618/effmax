$("#voltar").click(function (e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta

})

const formulario_post = document.getElementById("forma_pagamento");
let id_forma_pagamento = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')


//retorna os dados para o formulario
if (id_forma_pagamento.value == "") {
    $('#button_form').html('Cadastrar');
    $('#ativo').attr('checked', true);
    $(".title .sub-title").html("Cadastrar forma pagamento")
} else {
    $('#button_form').html('Alterar');
    $(".title .sub-title").html("Editar forma pagamento")
    show(id_forma_pagamento.value) // funcao para retornar os dados para o formulario
}

//formulario para cadastro
$("#forma_pagamento").submit(function (e) {
    if (id_forma_pagamento.value == "") {//cadastrar
        e.preventDefault()
        var formulario = $(this);
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja cadastrar essa forma de pagamento",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario)
            }
        })
    } else {//editar
        e.preventDefault()
        var formulario = $(this);
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar essa forma de pagamento",
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
    }


})

function create(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_forma_pagamento=true&acao=create&" + dados.serialize(),
        url: "modal/configuracao/forma_pagamento/gerenciar_forma_pagamento.php",
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


            formulario_post.reset(); // redefine os valores do formulário
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


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_forma_pagamento=true&acao=update&" + dados.serialize(),
        url: "modal/configuracao/forma_pagamento/gerenciar_forma_pagamento.php",
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
        data: "formulario_forma_pagamento=true&acao=show&forma_pagamento_id=" + id,
        url: "modal/configuracao/forma_pagamento/gerenciar_forma_pagamento.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao").val($dados.valores['descricao'])
            $("#conta_financeira").val($dados.valores['conta_financeira'])
            $("#status").val($dados.valores['status_recebimento'])
            $("#classificacao").val($dados.valores['classficacao'])
            $("#tipo_pagamento").val($dados.valores['tipo_pagamento'])
            $("#numero_parcela").val($dados.valores['numero_parcela'])
            $("#prazo_fatura").val($dados.valores['prazo_fatura'])
            $("#intervalo_parcela").val($dados.valores['intervalo_parcela'])
            $("#desconto_maximo").val($dados.valores['desconto_maximo'])
            $("#taxa").val($dados.valores['taxa'])
            $("#tipo_pagamento_nf").val($dados.valores['tipo_pagamento_nf'])

            if (($dados.valores['ativo']) == "S") {//veiifcar se a forma de pagamento está ativa//se sim marcar o check como true
                $('#ativo').attr('checked', true);
            }
            if (($dados.valores['avista']) == "S") {
                $('#avista').attr('checked', true);
            }
            if (($dados.valores['default']) == "S") {
                $('#default').attr('checked', true);
            }

            if (($dados.valores['delivery']) == "S") {
                $('#delivery').attr('checked', true);
            }

            if (($dados.valores['tipo_taxa']) == "f") {
                $('#taxa_fixo').attr('checked', true);
            } else {
                $('#taxa_porcentagem').attr('checked', true);
            }

        }
    }

    function falha() {
        console.log("erro");
    }

}


$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_forma_pagamento'), // Para garantir que funcione no modal
        width: '100%',

    });
});


