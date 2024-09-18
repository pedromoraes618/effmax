//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "cadastro_subgrupo=true",
        url: "view/estoque/subgrupo_estoque/cadastro_subgrupo_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

//editar
$("#editar_subgrupo_estoque").submit(function(e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse SubGrupo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = editar_subgrupo(formulario)
        } 
    })

})

function editar_subgrupo(dados) {
 
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/estoque/subgrupo_estoque/gerenciar_subgrupo_estoque.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Subgrupo alterado com sucesso',
                showConfirmButton: false,
                timer: 1500

            })
              //recarregar tabela
        // $.ajax({
        //     type: 'GET',
        //     data: "consultar_subgrupo=inicial",
        //     url: "view/estoque/subgrupo_estoque/table/consultar_subgrupo_estoque.php",
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
    var id_subgrupo = document.getElementById("id_subgrupo").value
 //  var user_id = id_user_logado.value
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse subgrupo ?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = remover_subgrupo(id_subgrupo,user_logado)
        } 
    })

})


//remover tarefa
function remover_subgrupo(id_subgrupo,user_logado) {
    $.ajax({
        type: "POST",
        data: "remover_subgrupo_estoque=true&id_subgrupo=" + id_subgrupo+"&nome_usuario_logado="+user_logado,
        url: "modal/estoque/subgrupo_estoque/gerenciar_subgrupo_estoque.php",
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


//modal para adicionar observacao
$("#modal_delivery").click(function () {
    $.ajax({
        type: 'GET',
        data: "subgrupo_delivery=true",
        url: "view/include/subgrupo_estoque/subgrupo_delivery.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_subgrupo_delivery").modal('show');
        },
    });
});

// $("#cfop_interno").select2()
// $("#cfop_externo").select2()
// $("#grupo_estoque").select2()