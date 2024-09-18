$("#cadastrar_subgrupo_estoque").submit(function(e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse SubGrupo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_subgrupo(formulario)
        } 
    })


})


const cadastro_formulario = document.getElementById("cadastrar_subgrupo_estoque");
//formulario para cadstro
function cadastrar_subgrupo(dados) {
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
                title: 'Subgrupo cadastrado com sucesso',
                showConfirmButton: false,
                timer: 1500
            })
        //resetar valores de input
        cadastro_formulario.reset()

        // //recarregar tabela
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