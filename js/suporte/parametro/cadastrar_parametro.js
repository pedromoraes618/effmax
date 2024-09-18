$("#cadastrar_parametro").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse Parâmetro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_parametro(cadastrar)
        } 
    })

})

const cadastro_formulario = document.getElementById("cadastrar_parametro");
function cadastrar_parametro(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/suporte/parametro/gerenciar_parametro.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Parâmetro cadastrado com sucesso',
                showConfirmButton: false,
                timer: 1500
            })
            //resetar valores de input
            cadastro_formulario.reset()

        //consultar tablela
        // $.ajax({
        // type: 'GET',
        // data: "consultar_parametro=inicial",
        // url: "view/suporte/parametro/table/consultar_parametro.php",
        // success: function(result) {
        // return $(".bloco-pesquisa-2 .tabela").html(result);
        // },
        // });
        $('#pesquisar_parametro').trigger('click'); // clicar automaticamente para realizar a consulta

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