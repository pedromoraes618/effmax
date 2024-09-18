$(".remover_frete").click(function (e) {//adicionar o produto na venda
    var frete_id = $(this).attr("id");
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover essa taxa de entrega?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
         
           var retorno = remover_frete(frete_id)
        }
    })

})

function remover_frete(frete_id) {

    $.ajax({
        type: "POST",
        data: "formulario_configuracao_delivery=true&acao=remover_frete&frete_id=" + frete_id ,
        url: "modal/delivery/configuracao/gerenciar_configuracao.php",
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
            
            $('#pesquisar_filtro_pesquisa_frete').trigger('click'); // clicar automaticamente para realizar a consulta

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