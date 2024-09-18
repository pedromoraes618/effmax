$("#voltar_consulta").click(function(e) {
$(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block") // remover tela de cadastro
$(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de consulta
    $.ajax({
    type: 'GET',
    data: "consultar_produto=inicial",
    url: "view/estoque/produto/consultar_produto.php",
    success: function(result) {
    return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
    },
});

})

const fomulario_produto = document.getElementById("cadastrar_produto");
//formulario para cadstro
$("#cadastrar_produto").submit(function(e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse produto?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_produto(formulario)
        } 
    })

})

function cadastrar_produto(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/estoque/produto/gerenciar_produto.php",
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
                timer: 3500
            })
            fomulario_produto.reset(); // redefine os valores do formulário
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
// //valor da pesquisa é guardado no localStorage, ao clicar em editar ou adicionar a pagina realizara a pesquisa novamente
// if (localStorage.getItem("pesquisar_produto")) {

//     let memoria_pesquisa = localStorage.getItem("pesquisar_produto");
//     conteudo_pesquisa.value = memoria_pesquisa
//     $.ajax({
//         type: 'GET',
//         data: "consultar_produto=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
//         url: "view/estoque/produto/table/consultar_produto.php",
//         success: function(result) {
//             return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
//         },
//     });
//   }