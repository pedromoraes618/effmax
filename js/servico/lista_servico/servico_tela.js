
const formulario_post = document.getElementById("servico");
let id_form = document.getElementById("id")

//retorna os dados para o formulario
if (id_form.value == "") {
    $(".title .sub-title").html("Adicionar Serviço")
} else {
    $(".title .sub-title").html("Alterar Serviço")
    show(id_form.value) // funcao para retornar os dados para o formulario
}

$(".btn-fechar").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
})



//formulario para cadastro
$("#servico").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (id_form.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar esse serviço?",
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
            text: "Deseja alterar esse serviço? ",
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
        data: "formulario_servico=true&acao=create&" + dados.serialize(),
        url: "modal/servico/lista_servico/gerenciar_lista_servico.php",
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


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_servico=true&acao=update&" + dados.serialize(),
        url: "modal/servico/lista_servico/gerenciar_lista_servico.php",
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
        data: "formulario_servico=true&acao=show&form_id=" + id,
        url: "modal/servico/lista_servico/gerenciar_lista_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao").val($dados.valores['descricao']);
            $("#valor").val($dados.valores['valor']);
            $("#status").val($dados.valores['status']);
        }
    }

    function falha() {
        console.log("erro");
    }

}


// $('#parceiro').select2({
//     dropdownParent: $('#modal_atendimento')
// });