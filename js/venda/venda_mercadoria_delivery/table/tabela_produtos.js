
$(".alterar_produto_vnd").click(function () {
    var prod_id = $(this).attr("produto_id")
    var id_item_nf = $(this).attr("id_item_nf")

    $.ajax({
        type: 'GET',
        data: "item_nf=true&acao=alterar_prod_nf&produto_delivery=true&produto_id="+prod_id+"&id_item_nf=" + id_item_nf + "&serie=vnd_delivery",
        url: "view/include/produto/produto_nf_delivery.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_item_nf").modal('show');
        },
    })
})

$(".incluir_observacao_prod").click(function () {
    var prod_id = $(this).attr("produto_id")
    var id_item_nf = $(this).attr("id_item_nf")

    $.ajax({
        type: 'GET',
        data: "adicionar_observacao_prd_delivery=true&acao=alterar_prod_nf&produto_delivery=true&produto_id="+prod_id+"&id_item_nf=" + id_item_nf + "&serie=vnd_delivery",
        url: "view/include/observacao/adicionar_observacao_produto_delivery.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_adiciona_observacao_prd_delivery").modal('show');
        },
    })
})

$(".remover_produto").click(function () {

    var id_item_nf = $(this).attr("id_item_nf")
    var id_produto = $(this).attr("id_produto")
    var quantidade_prod = $(this).attr("quantidade_prod")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse produto",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_item(codigo_nf.value, id_item_nf, id_produto, quantidade_prod, id_user_logado);
        }
    })

})

if (id_formulario.value != "") {
    $("#tabela_produtos .alterar_produto_vnd").remove()//remove o botão de editar o item quando a venda já está finalizado
    
}
