// var descricao_dl = $('#descricao_delivery').val()
// var img_produto = $('#img_produto').val()
// var descricao_dl_ext = $('#descricao_ext_delivery').val()

// $('#descricao_completo_delivery_modal').val(descricao_dl_ext);

var img_produto = $('#img_produto').val()

$('.bg-img-produto').attr({//adicionar a imagem na div
    'style': 'background-image: url("img/produto/' + img_produto + '")', // alterar a img na div
});

// $("#salvar_prod_delivery").click(function () {//adicionar observação no imput do formulario
//     // var valor_descricao_delivery = $('#descricao_delivery_modal').val();
//     // var valor_descricao_ext_delivery = $('#descricao_completo_delivery_modal').val();
//     // $('#descricao_delivery').val(valor_descricao_delivery);
//     // $('#descricao_ext_delivery').val(valor_descricao_ext_delivery);
//     // Swal.fire({
//     //     position: 'center',
//     //     icon: 'success',
//     //     title: "Informação salva com sucesso",
//     //     showConfirmButton: false,
//     //     timer: 3500
//     // })

// })


$("#produto_delivery").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse produto?",
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


})

function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_produto=true&acao=update_delivery&" + dados.serialize(),
        url: "modal/estoque/produto/gerenciar_produto.php",
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

// $('#modal_produto_delivery').on('hide.bs.modal', function (e) {
//     var valor_descricao_delivery = $('#descricao_delivery_modal').val();
//     var valor_descricao_ext_delivery = $('#descricao_completo_delivery_modal').val();
//     $('#descricao_delivery').val(valor_descricao_delivery);
//     $('#descricao_ext_delivery').val(valor_descricao_ext_delivery);
//     Swal.fire({
//         position: 'center',
//         icon: 'success',
//         title: "Informação salva com sucesso",
//         showConfirmButton: false,
//         timer: 3500
//     })
// });

$("#open_upload_img_prod").click(function () {
    $.ajax({
        type: 'GET',
        data: "upload_img_produto=true",
        url: "view/include/produto/produto_upload_img.php",
        success: function (result) {
            return $(".modal_externo_modal").html(result) + $("#modal_upload_produto_img").modal('show');

        },
    });
})

