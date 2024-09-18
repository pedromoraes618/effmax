$("#cadastrar_grupo_estoque").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse Grupo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_grupo(cadastrar)
        } 
    })



})

const cadastro_formulario = document.getElementById("cadastrar_grupo_estoque");
function cadastrar_grupo(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/estoque/grupo_estoque/gerenciar_grupo_estoque.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Grupo cadastrado com sucesso',
                showConfirmButton: false,
                timer: 1500
            })
            //resetar valores de input
            cadastro_formulario.reset()

        //consultar informação tabela
        // $.ajax({
        //     type: 'GET',
        //     data: "consultar_grupo=inicial",
        //     url: "view/estoque/grupo_estoque/table/consultar_grupo_estoque.php",
        //     success: function(result) {
        //         return $(".bloco-pesquisa-2 .tabela").html(result);
        //     },
        // });
        $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
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