// $("#voltar").click(function (e) {
//     // $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta
  
// })

const formulario_post = document.getElementById("conta_financeira");
let id_conta_financeira = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')


//retorna os dados para o formulario
if (id_conta_financeira.value == "") {
    $('#button_form').html('Cadastrar');
    $('#ativo').attr('checked', true);
    $(".title .sub-title").html("Cadastrar conta financeira")
} else {
    $('#button_form').html('Alterar');
    $(".title .sub-title").html("Editar conta financeira")
    show(id_conta_financeira.value) // funcao para retornar os dados para o formulario
}

//formulario para cadastro
$("#conta_financeira").submit(function (e) {
    if (id_conta_financeira.value == "") {//cadastrar
        e.preventDefault()
        var formulario = $(this);
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja cadastrar essa conta financeira",
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
            text: "Deseja alterar essa conta financeira",
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
        data: "formulario_conta_financeira=true&acao=create&" + dados.serialize(),
        url: "modal/configuracao/conta_financeira/gerenciar_conta_financeira.php",
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
        data: "formulario_conta_financeira=true&acao=update&" + dados.serialize(),
        url: "modal/configuracao/conta_financeira/gerenciar_conta_financeira.php",
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
        data: "formulario_conta_financeira=true&acao=show&conta_financeira_id=" + id,
        url: "modal/configuracao/conta_financeira/gerenciar_conta_financeira.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao").val($dados.valores['descricao'])
            $("#conta").val($dados.valores['conta'])
            $("#digito_conta").val($dados.valores['digito_conta'])
            $("#agencia").val($dados.valores['agencia'])
            $("#numero_banco").val($dados.valores['numero_banco'])

        }
    }

    function falha() {
        console.log("erro");
    }

}

