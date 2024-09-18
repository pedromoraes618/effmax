$("#cadastrar_tarefa").submit(function(e) {
    e.preventDefault()
    var cadastrar = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja adicionar essa tarefa?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_tarefa(cadastrar)
        } 
    })

})


const cadastro_formulario = document.getElementById("cadastrar_tarefa");
function cadastrar_tarefa(dados) {
   
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/lembrete/tarefa/gerenciar_tarefa.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
  
        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Taréfa Adicionada com sucesso',
                showConfirmButton: false,
                timer: 1500
            })

        //resetar valores de input
        cadastro_formulario.reset()
           
            // //realizar a consulta da tabela
            // $.ajax({
            //     type: 'GET',
            //     data: "consultar_tarefa=inicial&user_logado="+user_logado,
            //     url: "view/lembrete/tarefa/table/consultar_tarefa.php",
            //     success: function(result) {
            //         return $(".bloco-pesquisa-2 .tabela").html(result);
            //     },
            // });
            $('#pesquisar_tarefa').trigger('click'); // clicar automaticamente para realizar a consulta
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