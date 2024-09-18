$("#voltar_consulta").click(function () {
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display", "block") // remover tela de cadastro
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta
    $.ajax({
        type: 'GET',
        data: "consultar_cliente=inicial",
        url: "view/empresa/cliente/consultar_cliente.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });

})






// /*pegar o valor do codigo ibge do estado e informar ao input codigo estado */
// $("#estado").change(function(){
//     var valor_estado = document.getElementById("estado").value
//     var valor_codigo_estado = document.querySelector('#estado option[value="' + valor_estado +'"]').getAttribute('cdestado');
//     var estado_codigo_input = document.getElementById("estado_codigo");
//     estado_codigo_input.value = valor_codigo_estado

// })


// /*pegar o valor do codigo ibge da cidade e informar ao input codigo cidade */
// $("#cidade").change(function(){
//     var valor_cidade = document.getElementById("cidade").value
//     var valor_codigo_cidade = document.querySelector('#cidade option[value="' + valor_cidade +'"]').getAttribute('cdcidade');
//     var cidade_codigo_input = document.getElementById("cidade_codigo");
//     cidade_codigo_input.value = valor_codigo_cidade
// })



 const fomulario_cliente = document.getElementById("cadastrar_cliente");
 
//formulario para cadstro
$("#cadastrar_cliente").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cadastrar esse parceiro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = cadastrar_cliente(formulario)
        }
    })

})

function cadastrar_cliente(dados) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/empresa/cliente/gerenciar_cliente.php",
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
            fomulario_cliente.reset(); // redefine os valores do formulário
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