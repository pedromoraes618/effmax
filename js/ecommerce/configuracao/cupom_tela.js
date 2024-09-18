
const formulario_post = document.getElementById("cupom");
let id_form = document.getElementById("id")

//retorna os dados para o formulario
if (id_form.value == "") {
    $(".title .sub-title").html("Adicionar cupom")
} else {
    $(".title .sub-title").html("Alterar cupom")
    show(id_form.value) // funcao para retornar os dados para o formulario
}

$(".btn-fechar").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
})



//formulario para cadastro
$("#cupom").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (id_form.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar esse cupom?",
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
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse cupom? ",
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
        data: "formulario_configuracao_ecommerce=true&acao=create_cupom&" + dados.serialize(),
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
        data: "formulario_configuracao_ecommerce=true&acao=update_cupom&" + dados.serialize(),
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
        data: "formulario_configuracao_ecommerce=true&acao=show_cupom&form_id=" + id,
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao").val($dados.valores['descricao']);
            $("#cupom").val($dados.valores['cupom']);
            $("#valor").val($dados.valores['valor']);
            $("#status").val($dados.valores['status']);
            $("#validade_data_incial").val($dados.valores['data_validade_inicial']);
            $("#validade_data_final").val($dados.valores['data_validade_final']);

            $("#operador").val($dados.valores['operador']);
            $("#limite_cliente_cupom").val($dados.valores['limite_cliente']);

            $("#valor_minimo").val($dados.valores['valor_minimo']);
            $("#limite_utilizado").val($dados.valores['limite_utilizado']);
            $("#primeira_compra").val($dados.valores['primeira_compra']);

            var data_validade_final = $dados.valores['data_validade_final_view'];
            var limite_utilizado = $dados.valores['limite_utilizado'];
            var limite_cliente = $dados.valores['limite_cliente'];
            var condicao_cadastrado = $dados.valores['condicao_cadastrado'];
            if (limite_cliente != 1) {
                $('#limite_cliente_cupom').attr('checked', false);
            }

            if (data_validade_final != '' && data_validade_final !== null) {
                $('#sem_expiracao_validade').attr('checked', false);
                $('#validade_data_final').removeAttr('disabled');
            }
            if (limite_utilizado > 1) {
                $('#limite_total_cupom').attr('checked', true);
                $('#limite_utilizado').show();
            }

            if (condicao_cadastrado == 1) {
                $('#condicao_cadastrado').attr('checked', true);
            }

        }
    }

    function falha() {
        console.log("erro");
    }

}

$('#limite_total_cupom').change(function () {
    if ($(this).is(':checked')) {
        $('#limite_utilizado').show();
    } else {
        $('#limite_utilizado').hide();
    }
});

$('#sem_expiracao_validade').change(function () {
    if ($(this).is(':checked')) {
        $('#validade_data_final').attr('disabled', true);
    } else {
        $('#validade_data_final').removeAttr('disabled');
    }
});


// $('#parceiro').select2({
//     dropdownParent: $('#modal_atendimento')
// });

const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))