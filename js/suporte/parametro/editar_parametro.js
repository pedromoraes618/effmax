//voltar para tela de cadastro
$("#voltar_cadastro").click(function(e) {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)

    $.ajax({
        type: 'GET',
        data: "cadastro_parametro=true",
        url: "view/suporte/parametro/cadastro_parametro.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
})

//editar formulario
$("#editar_parametro").submit(function(e) {
    e.preventDefault()
    var editar = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar esse Parâmetro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = edt_parametro(editar)
        } 
    })


    
})

function edt_parametro(dados) {
 
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
                title: 'Parâmetro alterado com sucesso',
                showConfirmButton: false,
                timer: 1500

            })
            //consultar tabela
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

}d