
const formulario_post = document.getElementById("ajuste_credito");
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo")



//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_credito=inicial",
    url: "view/empresa/credito/table/consultar_credito.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {

    if (conteudo_pesquisa.value == "") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_credito=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
            url: "view/empresa/credito/table/consultar_credito.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
            },
        });
    }
})



$("#ajuste_credito").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja ajustar os valores de crédito?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = create(formulario)
        }
    })
})



function create(dados) {
    $.ajax({
        type: "POST",
        data: "ajustar_credito=true&acao=update&" + dados.serialize(),
        url: "modal/empresa/credito/gerenciar_credito.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
            formulario_post.reset(); // redefine os valores do formulário

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

