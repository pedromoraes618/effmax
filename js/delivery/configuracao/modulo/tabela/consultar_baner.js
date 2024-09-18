
$(".remover_baner").click(function () {//adicionar o produto na venda
    var codigo = $(this).attr('codigo')
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse baner?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            remover_baner(codigo)
        }
    })

})

function remover_baner(id) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_delivery=true&acao=remover_baner&id=" + id,
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
            tabela_baner()
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