
$('#upload_img').click(function () {

    var id_user_logado = document.getElementById('id_user_logado').value
    var user_logado = document.getElementById('user_logado').value

    $('#upload_img_user').ajaxForm({
        url: "modal/configuracao/meu_usuario/upload_img.php",
        type: 'POST',
        success: function (data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {
                update(id_user_logado,$dados.valores['name_arquivo'],user_logado)
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.valores,
                    timer: 7500,
    
                })
            }
        }
    }).submit();

});

//realizar o update na img do usuario
function update(id,img,user_logado) {
    $.ajax({
        type: "POST",
        data: "consultar_meu_user=true&acao=update&id_user=" + id +"&img="+img +"&user_logado="+user_logado,
        url: "modal/configuracao/meu_usuario/gerenciar_usuario.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.valores,
                showConfirmButton: false,
                timer: 3500
            })
            $('.bg-img-user').attr({
                'style': 'background-image: url("img/usuario/' + img + '")', // alterar a img na div
            });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.valores,
                timer: 7500,

            })
        }
    }

    function falha() {
        console.log("erro");
    }

}