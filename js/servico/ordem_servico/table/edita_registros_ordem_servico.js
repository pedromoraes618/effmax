var form_id = document.getElementById("id")

//abrir a pagina de edição do formulario, pegando o id 
$(".cancelar_taxa").click(function () {
    var taxa_id = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja cancelar essa taxa??",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelar_taxa(taxa_id, form_id.value, false)
        }
    })
})


function cancelar_taxa(taxa_id, form_id, check_autorizador) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=cancelar_taxa&id=" + taxa_id + "&check_autorizador=" + check_autorizador,
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
            consultar_tabela_registro_ordem_servico(form_id)
            show_valores(form_id)
        } else if ($dados.sucesso == "autorizar") {//validar autorizacao ao adicionar o produto
            $.ajax({
                type: 'GET',
                data: "autorizar_acao=true&acao=autorizar_cancelamento_financeiro&mensagem=" + $dados.title,
                url: "view/include/autorizacao/autorizar_acao.php",
                success: function (result) {
                    return $(".modal_externo_2").html(result)
                        + $("#modal_autorizar_acao").modal('show')
                        + $("#autorizar_acao").addClass("autorizar_cancelamento_financeiro")
                        + $("#autorizar_acao").attr("data-lcf-id", taxa_id)
                        + $('.modal-backdrop').remove();

                },
            });
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


