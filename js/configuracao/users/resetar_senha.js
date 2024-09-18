$("#voltar_cadastro").click(function(e) {
    // $('.bloco-pesquisa-menu.bloco-pesquisa-1').css("display", 'none')
    // $('.bloco-pesquisa-menu .bloco-pesquisa-1').fadeIn(200)

    $.ajax({
        type: 'GET',
        data: "cadastrar=",
        url: "view/configuracao/users/cadastrar_user.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})


//editar usuario
$("#resetar_senha_usuario").submit(function(e) {

    e.preventDefault()
    var resetar_senha = $(this);
    var retorno = resetar_senha_usuario(resetar_senha)

})

function resetar_senha_usuario(dados) {
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
                title: 'Senha resetada com sucesso',
                showConfirmButton: false,
                timer: 1500


            })
           //consultar inicial
           $.ajax({
                type: 'GET',
                data: "consultar_user=inicial",
                url: "view/configuracao/users/table/consultar_user.php",
                success: function(result) {
                    return $(".tabela").html(result);
                },
            });



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