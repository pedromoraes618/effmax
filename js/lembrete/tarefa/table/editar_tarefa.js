$(".editar_tarefa").click(function(e) {
    let id_tarefa = $(this).attr("id_tarefa")

    $.ajax({
        type: 'GET',
        data: "editar_tarefa=true&id_tarefa=" + id_tarefa,
        url: "view/lembrete/tarefa/editar_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

//remover dados do fomulario
$(".remover_tarefa").click(function(e){
    e.preventDefault()
 
    var id_tarefa = $(this).attr("id_tarefa")
 
 //  var user_id = id_user_logado.value
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover essa tarefa?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = remove_tarefa(id_tarefa,user_logado)
        } 
    })

})

//remover tarefa
function remove_tarefa(id_tarefa,user_logado) {
    $.ajax({
        type: "POST",
        data: "remover_tarefa=true&id_tarefa=" + id_tarefa+"&nome_usuario_logado="+user_logado,
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
                title: 'Tarefa Removida com sucesso',
                showConfirmButton: false,
                timer: 1500


            })
            //consultar informaçãoes para tabela
            $.ajax({
                type: 'GET',
                data: "consultar_tarefa=inicial&user_logado="+user_logado,
                url: "view/lembrete/tarefa/table/consultar_tarefa.php",
                success: function(result) {
                    return $(".bloco-pesquisa-2 .tabela").html(result);
                },
            });
            //voltar para tela de cadastrs
            $.ajax({
                type: 'GET',
                data: "cadastro_tarefa=true",
                url: "view/lembrete/tarefa/cadastro_tarefa.php",
                success: function(result) {
                    return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
                },
            });

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