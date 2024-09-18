//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    $.ajax({
        type: 'GET',
        data: "cadastro_tarefa=true",
        url: "view/lembrete/tarefa/cadastro_tarefa.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

//remover dados do fomulario
$("#remover").click(function(e){
    e.preventDefault()
    var id_tarefa = document.getElementById("id_tarefa").value
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

//editar dados do formulario
$("#editar_tarefa").submit(function(e) {
    e.preventDefault()
    var editar_formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa tarefa?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = edtarefa(editar_formulario)
        } 
    })

})


function edtarefa(dados) {
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
                title: 'Tarefa alterada com sucesso',
                showConfirmButton: false,
                timer: 1500


            })
            // //consultar informaçãoes
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
            // $.ajax({
            //     type: 'GET',
            //     data: "consultar_tarefa=inicial&user_logado="+user_logado,
            //     url: "view/lembrete/tarefa/table/consultar_tarefa.php",
            //     success: function(result) {
            //         return $(".bloco-pesquisa-2 .tabela").html(result);
            //     },
            // });

            $('#pesquisar_tarefa').trigger('click'); // clicar automaticamente para realizar a consulta
            
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