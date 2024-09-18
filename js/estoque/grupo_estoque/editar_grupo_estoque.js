//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "cadastro_grupo=true",
        url: "view/estoque/grupo_estoque/cadastro_grupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

//editar usuario
$("#editar_grupo_estoque").submit(function(e) {
    e.preventDefault()
    var editar = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse Grupo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = editar_grupo(editar)
        } 
    })


  
})

function editar_grupo(dados) {
 
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
                title: 'Grupo alterada com sucesso',
                showConfirmButton: false,
                timer: 1500

            })
      
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


//remover dados do fomulario
$("#remover").click(function(e){
    e.preventDefault()
    var id_grupo = document.getElementById("id_grupo").value
 //  var user_id = id_user_logado.value
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse Grupo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = remover_grupo(id_grupo,user_logado)
        } 
    })

})


//remover tarefa
function remover_grupo(id_grupo,user_logado) {
    $.ajax({
        type: "POST",
        data: "remover_grupo_estoque=true&id_grupo=" + id_grupo+"&nome_usuario_logado="+user_logado,
        url: "modal/estoque/grupo_estoque/gerenciar_grupo_estoque.php",
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

