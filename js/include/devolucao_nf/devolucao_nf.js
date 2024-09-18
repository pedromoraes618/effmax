
var tipo = $('#tipo').val()
var id = $('#id').val()
show(id, tipo)
tabela_itens(id, tipo)
//mostrar as informações no formulario show
$("#devolucao_nf").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja finalizar essa devolução?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = create(formulario, tipo)
        }
    })
})

//mostrar as informações no formulario show
function create(dados, tipo) {
    $.ajax({
        type: "POST",
        data: "devolucao_nf=true&acao=create&" + dados.serialize() + "&tipo=" + tipo,
        url: "modal/devolucao_nf/devolucao_mercadoria/gerenciar_devolucao.php",
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
            setTimeout(function () {
                $('.btn-close').trigger('click'); // clicar automaticamente para fechar o modal
                $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta

            }, 1000);

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

function show(id, tipo) {
    $.ajax({
        type: "POST",
        data: "devolucao_nf=true&acao=show&form_id=" + id + "&tipo=" + tipo,
        url: "modal/devolucao_nf/devolucao_mercadoria/gerenciar_devolucao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#parceiro").val($dados.valores['parceiro'])
            $("#doc").val($dados.valores['doc'])
            $("#valor_doc").val($dados.valores['valor_doc'])
        }
    }

    function falha() {
        console.log("erro");
    }

}



function tabela_itens(id, tipo) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "nf_itens=true&form_id=" + id + "&tipo=" + tipo,
        url: "view/include/devolucao_nf/table/tabela_itens.php",
        success: function (result) {
            return $(".tabela_itens").html(result)

        },
    })
}
