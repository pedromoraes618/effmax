
$('#upload_img').click(function () {

    // var id_user_logado = document.getElementById('id_user_logado').value
    // var user_logado = document.getElementById('user_logado').value
    // var produto_id = document.getElementById('id').value

    $('#upload_img_subgrupo_estoque').ajaxForm({
        url: "modal/estoque/subgrupo_estoque/upload_img.php",
        type: 'POST',
        success: function (data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {

                $('.bg-img-subgrupo-estoque').attr({
                    'style': 'background-image: url("img/subgrupo_estoque/' + $dados.valores['name_arquivo'] + '")', // alterar a img na div
                });
                $("#img_subgrupo_estoque").val($dados.valores['name_arquivo'])
                $('#fechar_modal_upload_img_subrupo_estoque').trigger('click'); // clicar automaticamente para realizar fechar o modal


                //   update(id_user_logado, $dados.valores['name_arquivo'], user_logado)
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
// //realizar o update na img do usuario
// function update(id,img,user_logado) {
//     $.ajax({
//         type: "POST",
//         data: "consultar_meu_user=true&acao=update&id_user=" + id +"&img="+img +"&user_logado="+user_logado,
//         url: "modal/configuracao/meu_usuario/gerenciar_usuario.php",
//         async: false
//     }).then(sucesso, falha);

//     function sucesso(data) {
//         $dados = $.parseJSON(data)["dados"];
//         if ($dados.sucesso == true) {
//             Swal.fire({
//                 position: 'center',
//                 icon: 'success',
//                 title: $dados.valores,
//                 showConfirmButton: false,
//                 timer: 3500
//             })
//             $('.bg-img-user').attr({
//                 'style': 'background-image: url("img/usuario/' + img + '")', // alterar a img na div
//             });
//         }else{
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Verifique!',
//                 text: $dados.valores,
//                 timer: 7500,

//             })
//         }
//     }

//     function falha() {
//         console.log("erro");
//     }

// }