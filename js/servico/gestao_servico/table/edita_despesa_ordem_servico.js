var form_id = document.getElementById("id")

//abrir a pagina de edição do formulario, pegando o id 
$(".cancelar_despesa").click(function () {
    var despesa_id = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa despesa?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelar_despesa(despesa_id, form_id.value)
        }
    })
})


function cancelar_despesa(despesa_id, form_id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=cancelar_despesa&id=" + despesa_id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
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
            consultar_tabela_despesa_ordem_servico(form_id)
            show_valores(form_id)
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


