
$(".alterar_item_pedido").click(function () {
    var item_id = $(this).data("item-id")
    show_item(item_id)
})

$(".remover_produto").click(function () {
    var item_id = $(this).data("item-id")

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
            destroy_item(item_id, codigo_nf.value);
        }
    })
})