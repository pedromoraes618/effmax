$(".remover_marcador").click(function () {
    var marcador_id = $(this).data("id")
    remover_marcador(marcador_id)
})
function remover_marcador(marcador_id) {
    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=remove_marcador&id=" + marcador_id,
        url: "modal/estoque/produto/gerenciar_produto.php",
        async: false
    }).then(sucesso, falha);
    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            consultar_marcadores(codigo_nf.value)
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