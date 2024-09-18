$("#cadastrar_serie").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar essa serie?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_serie(cadastrar)
        } 
    })



})

const cadastro_formulario = document.getElementById("cadastrar_serie");
function cadastrar_serie(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/suporte/serie/gerenciar_serie.php",
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
                text: $dados.title,
                timer: 7500,
            

            })

        }
    }

    function falha() {
        console.log("erro");
    }

}