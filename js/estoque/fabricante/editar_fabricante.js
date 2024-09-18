//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "cadastro_fabricante=true",
        url: "view/estoque/fabricante/cadastro_fabricante.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})


//remover dados do fomulario
$("#remover").click(function(e){
    e.preventDefault()
    var id_fabricante = document.getElementById("id_fabricante").value
 //  var user_id = id_user_logado.value
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse fabricante?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = remover_fabricante(id_fabricante,user_logado)
        } 
    })

})


//remover tarefa
function remover_fabricante(id_fabricante,user_logado) {
    $.ajax({
        type: "POST",
        data: "remover_fabricante=true&id_fabricante=" + id_fabricante+"&nome_usuario_logado="+user_logado,
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


//editar usuario
$("#editar_fabricante").submit(function(e) {
    e.preventDefault()
    var editar = $(this);
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse fabricante?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = editar_fabricante(editar)
        } 
    })

 
})

function editar_fabricante(dados) {
 
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
                timer: 1500

            })
      
        // //consultar informação tabela
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


