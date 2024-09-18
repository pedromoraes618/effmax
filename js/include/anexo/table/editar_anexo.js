
$(".remover_arquivo").click(function () {
    var id = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse arquivo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_arquivo(id)
        }
    })
});

function delete_arquivo(id) {
    $.ajax({
        type: "POST",
        data: "formulario_anexo=true&acao=remover_arquivo&form_id=" + id,
        url: "modal/anexo/gerenciar_anexo.php",
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
            $('#pesquisar_filtro_pesquisa_anexo').trigger('click'); // clicar automaticamente para realizar a consulta
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
