//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "cadastro_subcategoria=true",
        url: "view/suporte/tela/cadastro_subcategoria.php",
        success: function(result) {
            return $(".bloco-cadastro-1").html(result);
        },
    });
})

//editar usuario
$("#editar_subcategoria").submit(function(e) {
    e.preventDefault()
    var editar_subcategoria = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essa subcategoria?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
             var retorno = edt_subcategoria(editar_subcategoria)
        } 
    })
})

function edt_subcategoria(dados) {
   
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/suporte/tela/gerenciar_tela.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $sucesso = $.parseJSON(data)["sucesso"];
        $mensagem = $.parseJSON(data)["mensagem"];
        if ($sucesso) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Subcategoria alterada com sucesso',
                showConfirmButton: false,
                timer: 1500


            })
            //consultar categorias já cadastradas
            // $.ajax({
            // type: 'GET',
            // data: "consultar_tela_subcategoria=inicial",
            // url: "view/suporte/tela/table/consultar_subcategoria.php",
            // success: function(result) {
            // return $(".tabela").html(result);
            // },
            // });
            $('#pesquisa_conteudo').trigger('click'); // clicar automaticamente para realizar a consulta

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