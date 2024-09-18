$("#cadastrar_subcategoria").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar essa subcategoria?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_subcategoria(cadastrar)
        } 
    })


})


const cadastro_formulario = document.getElementById("cadastrar_subcategoria");
function cadastrar_subcategoria(dados) {
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
                title: 'Subcategoria cadastrada com sucesso',
                showConfirmButton: false,
                timer: 1500
            })

            //consultar subcategorias já cadastradas
            // $.ajax({
            //     type: 'GET',
            //     data: "consultar_tela_subcategoria=inicial",
            //     url: "view/suporte/tela/table/consultar_subcategoria.php",
            //     success: function(result) {
            //         return $(".table").html(result);
            //     },
            // });
            $('#pesquisa_conteudo').trigger('click'); // clicar automaticamente para realizar a consulta

           //RESETAR VALROES DOS INPUTs
           cadastro_formulario.reset()



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