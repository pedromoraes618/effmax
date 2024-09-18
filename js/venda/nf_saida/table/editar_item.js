//modal para editar o item
$(".editar_item_nf").click(function () {

    var item_id = $(this).attr("id_item_nf")
    var codigo_nf = $(this).attr("codigo_nf")

    $.ajax({
        type: 'GET',
        data: "produto_nf_saida=true&item_id=" + item_id + "&codigo_nf=" + codigo_nf + "&acao=update",
        url: "view/include/produto_nf/produto_nf_saida.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_produto").modal('show');

        },
    });
});




//modal para editar o item
$(".remover_item_nf").click(function () {

    var item_id = $(this).attr("id_item_nf")
    var codigo_nf = $(this).attr("codigo_nf")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse item",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_item(item_id, codigo_nf);
        }
    })
});

function delete_item(id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=delete_item&id_item_nf=" + id,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
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
            show_valores(codigo_nf)
            tabela_produtos_nf(codigo_nf)//retornar a tabela dos itens atualizado
            calcularTotal()
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