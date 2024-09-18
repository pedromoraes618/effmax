$("#cadastrar_fabricante").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse fabricante?",
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

const cadastro_formulario = document.getElementById("cadastrar_fabricante");
function cadastrar_grupo(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/estoque/fabricante/gerenciar_fabricante.php",
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

        //resetar valores de input
        cadastro_formulario.reset()

        //consultar informação tabela
        // $.ajax({
        //     type: 'GET',
        //     data: "consultar_fabricante=inicial",
        //     url: "view/estoque/fabricante/table/consultar_fabricante.php",
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