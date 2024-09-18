
$("#adicionar_frete").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_frete=true",
        url: "view/include/delivery/frete.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_adicionar_frete").modal('show')
        },
    });
})

$.ajax({
    type: 'GET',
    data: "consultar_tabela_frete=inicial",
    url: "view/delivery/configuracao/modulo/tabela/consulta_frete.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});

$('#pesquisar_filtro_pesquisa_frete').click(function(){
    var consulta = $('#pesquisa_conteudo_frete').val()
    var promocao = $('#promocao').val()
    $.ajax({
        type: 'GET',
        data: "consultar_tabela_frete=detalhado&pesquisa="+consulta+"&promocao="+promocao,
        url: "view/delivery/configuracao/modulo/tabela/consulta_frete.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})

$("#frete").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas informações?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
         
           var retorno = update_frete(formulario)
        }
    })

})


    
function update_frete(dados) {
    
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_delivery=true&acao=frete&" + dados.serialize(),
        url: "modal/delivery/configuracao/gerenciar_configuracao.php",
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
            $('#pesquisar_filtro_pesquisa_frete').trigger('click'); // clicar automaticamente para realizar a consulta

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
    
