
$(".alterar_produto_vnd").click(function () {
    var prod_id = $(this).attr("produto_id")
    $.ajax({
        type: 'GET',
        data: "item_nf=true&acao=alterar_prod_nf&id_item_nf=" + prod_id + "&serie=vnd",
        url: "view/include/produto/produto_nf.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_item_nf").modal('show');
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
           delete_item(codigo_nf.value,id_item_nf,id_produto,quantidade_prod,id_user_logado);
        }
    })

})

if ($('#id').val()!="") {

    $("#tabela_produtos .alterar_produto_vnd").remove()//remove o botão de editar o item quando a venda já está finalizado
    
}
