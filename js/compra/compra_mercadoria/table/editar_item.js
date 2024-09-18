//modal para editar o item
$(".editar_item_nf").click(function () {

    var item_id = $(this).attr("item_id")
    var codigo_nf = $(this).attr("codigo_nf")

    $.ajax({
        type: 'GET',
        data: "produto_nf_entrada=true&item_id=" + item_id + "&codigo_nf=" + codigo_nf + "&acao=update",
        url: "view/include/produto_nf/produto_nf_entrada.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_produto").modal('show');

        },
    });
});




//modal para editar o item
$(".remover_item").click(function () {

    var item_id = $(this).attr("item_id")
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
