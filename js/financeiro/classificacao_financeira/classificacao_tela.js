
const formulario_post = document.getElementById("classificacao_financira");
let id_form = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')


//retorna os dados para o formulario
if (id_form.value == "") {
    $('#button_form').html('Cadastrar');
    $('#ativo').attr('checked', true);
    $(".title .sub-title").html("Cadastrar Classificação Financeira")
} else {
    $('#button_form').html('Alterar');
    $(".title .sub-title").html("Editar Classificação Financeira")

    show(id_form.value) // funcao para retornar os dados para o formulario
    $("#remover_registro").click(function () {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja remover essa Classificação?",
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
}

//formulario para cadastro
$("#classificacao_financira").submit(function (e) {
    if (id_form.value == "") {//cadastrar
        e.preventDefault()
        var formulario = $(this);
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja Adicionar essa Classificação?",
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
            text: "Deseja alterar essa Classificação? ",
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
        data: "formulario_classificacao_financeira=true&acao=create&" + dados.serialize(),
        url: "modal/financeiro/classificacao_financeira/gerenciar_classificacao.php",
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
        data: "formulario_classificacao_financeira=true&acao=update&" + dados.serialize(),
        url: "modal/financeiro/classificacao_financeira/gerenciar_classificacao.php",
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

function remover(id) {
    $.ajax({
        type: "POST",
        data: "formulario_classificacao_financeira=true&acao=delete&form_id=" + id,
        url: "modal/financeiro/classificacao_financeira/gerenciar_classificacao.php",
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
        data: "formulario_classificacao_financeira=true&acao=show&form_id=" + id,
        url: "modal/financeiro/classificacao_financeira/gerenciar_classificacao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao").val($dados.valores['descricao'])
        }
    }

    function falha() {
        console.log("erro");
    }

}

