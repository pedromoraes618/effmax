$("#cadastrar_categoria").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse categoria?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_categoria(cadastrar)
        } 
    })

  
})



const cadastro_formulario = document.getElementById("cadastrar_categoria");
function cadastrar_categoria(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/suporte/tela/gerenciar_tela.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Categoria cadastrada com sucesso',
                showConfirmButton: false,
                timer: 1500
            })
            //resetar valores de input
            cadastro_formulario.reset()

            // //consultar categorias já cadastradas
            // $.ajax({
            //     type: 'GET',
            //     data: "consultar_tela_categoria=inicial",
            //     url: "view/suporte/tela/table/consultar_categoria.php",
            //     success: function(result) {
            //         return $(".table").html(result);
            //     },
            // });

            $('#pesquisa_conteudo').trigger('click'); // clicar automaticamente para realizar a consulta
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