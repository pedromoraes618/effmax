
const formulario_post = document.getElementById("atendimento");
let id_form = document.getElementById("id")

//retorna os dados para o formulario
if (id_form.value == "") {
    $(".title .sub-title").html("Registrar Atendimento")
} else {
    $(".title .sub-title").html("Alterar Atendimento")
    show(id_form.value) // funcao para retornar os dados para o formulario
}
$("#remover").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse atendimento?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            remover(id_form.value)
        }
    })

})

//formulario para cadastro
$("#atendimento").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (id_form.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar esse atendimento?",
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
            text: "Deseja alterar esse atendimento? ",
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
        data: "formulario_atendimento=true&acao=create&" + dados.serialize(),
        url: "modal/empresa/atendimento/gerenciar_atendimento.php",
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
            $('#pesquisar_filtro_pesquisa_contato_parceiro').trigger('click'); // clicar automaticamente para realizar a consulta
            $('.btn-close-atendimento').trigger('click'); // clicar automaticamente para realizar a consulta

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
        data: "formulario_atendimento=true&acao=update&" + dados.serialize(),
        url: "modal/empresa/atendimento/gerenciar_atendimento.php",
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
            $('#pesquisar_filtro_pesquisa_contato_parceiro').trigger('click'); // clicar automaticamente para realizar a consulta

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

function remover(id) {
    $.ajax({
        type: "POST",
        data: "formulario_atendimento=true&acao=delete&form_id=" + id,
        url: "modal/empresa/atendimento/gerenciar_atendimento.php",
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
            $('.btn-close-atendimento').trigger('click'); // clicar automaticamente para realizar a consulta
            $('#pesquisar_filtro_pesquisa_contato_parceiro').trigger('click'); // clicar automaticamente para realizar a consulta

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
        data: "formulario_atendimento=true&acao=show&form_id=" + id,
        url: "modal/empresa/atendimento/gerenciar_atendimento.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#atendente").val($dados.valores['usuario_id'])
            $("#parceiro").val($dados.valores['parceiro_id'])
            $("#agendar").val($dados.valores['data_agendamento'])
            $("#visualizacao").val($dados.valores['visualizar'])
            $("#status_atd").val($dados.valores['status_id'])
            $("#descricao").val($dados.valores['descricao'])
        }
    }

    function falha() {
        console.log("erro");
    }

}


$('#parceiro').select2({
    dropdownParent: $('#modal_atendimento')
});