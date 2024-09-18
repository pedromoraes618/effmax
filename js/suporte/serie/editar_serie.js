//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)
    $.ajax({
        type: 'GET',
        data: "cadastro_serie=true",
        url: "view/suporte/serie/cadastro_serie.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

//editar usuario
$("#editar_serie").submit(function(e) {
    e.preventDefault()
    var editar = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa serie",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = editar_serie(editar)
        } 
    })


  
})

function editar_serie(dados) {
 
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/suporte/serie/gerenciar_serie.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
    
        if ($dados.sucesso==true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
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
                text: $dados.title,
                timer: 7500,
            
            })

        }
    }

    function falha() {
        console.log("erro");
    }

}d