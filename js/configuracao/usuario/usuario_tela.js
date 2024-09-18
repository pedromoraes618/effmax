
const formulario_post = document.getElementById("usuario");
let id_form = document.getElementById("id")

//retorna os dados para o formulario
if (id_form.value == "") {
    $(".title .sub-title").html("Adicionar Usuário")
} else {
    $(".title .sub-title").html("Alterar Usuário")
    show(id_form.value) // funcao para retornar os dados para o formulario
}

$(".btn-fechar").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
})



//formulario para cadastro
$("#usuario").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (id_form.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar esse usuário?",
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
            text: "Deseja alterar esse usuário? ",
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
        data: "formulario_usuario=true&acao=create&" + dados.serialize(),
        url: "modal/configuracao/usuario/gerenciar_usuario.php",
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
        data: "formulario_usuario=true&acao=update&" + dados.serialize(),
        url: "modal/configuracao/usuario/gerenciar_usuario.php",
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
        data: "formulario_usuario=true&acao=show&form_id=" + id,
        url: "modal/configuracao/usuario/gerenciar_usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#nome").val($dados.valores['nome']);
            $("#usuario").val($dados.valores['usuario']);
            $("#perfil").val($dados.valores['perfil']);
            $("#situacao").val($dados.valores['situacao']);
            $("#email").val($dados.valores['email']);
            $("#cargo").val($dados.valores['cargo']);
            $("#restricao_horario").val($dados.valores['restricao_horario']);
            $("#comissao").val($dados.valores['comissao']);

            if (($dados.valores['cancelar_venda']) == "SIM") {
                $('#cancelar_venda').attr('checked', true);
            }
            if (($dados.valores['receber_alerta']) == "SIM") {
                $('#receber_alerta').attr('checked', true);
            }
            if (($dados.valores['cancelar_pedido']) == "SIM") {
                $('#cancelar_pedido').attr('checked', true);
            }
            if (($dados.valores['autorizar_desconto']) == "SIM") {
                $('#autorizar_desconto').attr('checked', true);
            }

            if (($dados.valores['autorizar_dados_pedido_loja']) == "SIM") {
                $('#autorizar_dados_pedido_loja').attr('checked', true);
            }
            if (($dados.valores['cancelar_lancamento_financeiro']) == "SIM") {
                $('#cancelar_lancamento_financeiro').attr('checked', true);
            }

            if (($dados.valores['remover_faturamento']) == "SIM") {
                $('#remover_faturamento').attr('checked', true);
            }

            if (($dados.valores['vendedor']) == "SIM") {
                $('#vendedor').attr('checked', true);
            }
            if (($dados.valores['tecnico']) == "SIM") {
                $('#tecnico').attr('checked', true);
            }
            if (($dados.valores['comprador']) == "SIM") {
                $('#comprador').attr('checked', true);
            }



        }
    }

    function falha() {
        console.log("erro");
    }

}


$(".resetar_senha").click(function () {
    $.ajax({
        type: 'GET',
        data: "usuario=true",
        url: "view/configuracao/usuario/resetar_senha_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show_2").html(result) + $("#modal_resetar_senha").modal('show');

        },
    });
})
