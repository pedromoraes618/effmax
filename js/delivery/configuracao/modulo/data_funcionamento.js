$("#data_funcionamento").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar a data de funcionamento do delivery?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = update_data_funcionamento(formulario)
        }
    })

})

    
function update_data_funcionamento(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_delivery=true&acao=data_funcionamento&" + dados.serialize(),
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