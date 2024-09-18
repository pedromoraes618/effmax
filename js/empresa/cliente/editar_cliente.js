$("#voltar_consulta").click(function (e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block") // remover tela de cadastro
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta
    $.ajax({
        type: 'GET',
        data: "consultar_cliente=inicial",
        url: "view/empresa/cliente/consultar_cliente.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });

})




//formulario para editar
$("#editar_cliente").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse parceiro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = editar_cliente(formulario)
        }
    })

})


function editar_cliente(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/empresa/cliente/gerenciar_cliente.php",
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
