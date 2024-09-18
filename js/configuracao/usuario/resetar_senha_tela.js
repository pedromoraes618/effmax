let id_form = document.getElementById("id")

//formulario para cadastro
$("#resete_senha").submit(function (e) {
  
    e.preventDefault()
    var formulario = $(this);
    if (id_form.value != "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja resetar a senha?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = resetar_senha(formulario)
            }
        })
    }
})

function resetar_senha(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_usuario=true&acao=resetar_senha&id=" + id_form.value + "&" + dados.serialize(),
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
            $('.btn-fechar-resetar').trigger('click'); // clicar automaticamente para realizar a consulta
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

