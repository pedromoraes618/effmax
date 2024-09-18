var formulario_post_frete_adicionar = document.getElementById("adicionar_frete_delivery")
$("#adicionar_frete_delivery").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja adicionar essa taxa de entrega?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = adicionar_frete(formulario);
        }
    })
})



function adicionar_frete(dados) {

    $.ajax({
        type: "POST",
        data: "formulario_configuracao_delivery=true&acao=adicionar_frete&" + dados.serialize(),
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
            formulario_post_frete_adicionar.reset(); // redefine os valores do formulário
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