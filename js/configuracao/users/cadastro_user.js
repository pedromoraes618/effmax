$("#cadastrar_usuario").submit(function(e) {

    e.preventDefault()
    var cadastrar_user = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse Usuário?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_usuario(cadastrar_user)
        } 
    })




})


const cadastro_formulario = document.getElementById("cadastrar_usuario");
function cadastrar_usuario(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/configuracao/users/usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Usuário cadastrado com sucesso',
                showConfirmButton: false,
                timer: 1500
            })

              //resetar valores de input
              cadastro_formulario.reset()

            // //consultar inicial
            // $.ajax({
            //     type: 'GET',
            //     data: "consultar_user=inicial",
            //     url: "view/configuracao/users/table/consultar_user.php",
            //     success: function(result) {
            //         return $(".tabela").html(result);
            //     },
            // });
            $('#pesquisar_user').trigger('click'); // clicar automaticamente para realizar a consulta

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $mensagem,
                timer: 7500,
            

            })

        }
    }

    function falha() {
        console.log("erro");
    }

}