$(document).ready(function () {
    var id_user_logado = document.getElementById('id_user_logado').value
    show(id_user_logado)
})


$("#alterar_senha").click(function () {
    var id_user_logado = document.getElementById('id_user_logado').value
    var user_logado = document.getElementById('user_logado').value
    var senha_nova = document.getElementById('senha_nova').value
    var confirmar_senha = document.getElementById('confirmar_senha').value
    const dados_input = {
        "id_user_logado": id_user_logado,
        "user_logado": user_logado,
        "senha_nova": senha_nova,
        "confirmar_senha": confirmar_senha
    }

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja resetar a sua senha?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            update_senha(dados_input)
        }
    })
 
})

$("#open_upload_img_user").click(function () {

    $.ajax({
        type: 'GET',
        data: "upload_img_user=true",
        url: "view/configuracao/meu_usuario/modal_upload_img.php",
        success: function (result) {
            return $(".acao .modal_show").html(result) + $("#modal_upload_img_user").modal('show');

        },
    });
})

//retornar as informações
function show(id) {
    $.ajax({
        type: "POST",
        data: "consultar_meu_user=true&acao=show&id_user=" + id,
        url: "modal/configuracao/meu_usuario/gerenciar_usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $('.bg-img-user').attr({
                'style': 'background-image: url("img/usuario/' + $dados.valores['img'] + '")',
            });
            $(".descricao_nome").html($dados.valores['nome'])
            $(".descricao_user").html($dados.valores['usuario'])
            $(".descricao_tipo").html($dados.valores['tipo_usuario'])
            $(".descricao_email").html($dados.valores['email'])

        }
    }

    function falha() {
        console.log("erro");
    }

}


//resetar senha
function update_senha(dados_input) {
    $.ajax({
        type: "POST",
        data: "consultar_meu_user=true&acao=update_senha&id_user=" + dados_input.id_user_logado + "&user_logado=" + dados_input.user_logado  + "&nova_senha=" + dados_input.senha_nova + "&confirmar_senha=" + dados_input.confirmar_senha,
        url: "modal/configuracao/meu_usuario/gerenciar_usuario.php",
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
            $('#senha_atual').val('')
            $('#senha_nova').val('')
            $('#confirmar_senha').val('')

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
