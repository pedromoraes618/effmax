
//abrir a pagina de edição do formulario, pegando o id 
$(".editar_servico").click(function () {
    var id = $(this).attr("id")
    show_det_servico(id)
    consultar_tabela_equipe(id)
    $('#modal_servicos_ordem_servico').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;


})


$(".remover_servico").click(function () {
    var id = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse serviço?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            remover_servico(id, form_id.value)
        }
    })
})


function remover_servico(id, form_id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=remover_servico&item_id=" + id,
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
            consultar_tabela_servico(form_id)
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


function consultar_tabela_servico(id) {

    $.ajax({
        type: 'GET',
        data: "servico_1_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_servicos_ordem_servico.php",
        success: function (result) {
            return $(".tabela_servicos").html(result);

        },
    });
}


//mostrar as informações no formulario show
function show_det_servico(id) {
    $(".modal-body #carregando").css("display", "block")

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=show_det_servicoa&id=" + id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $(".modal-body #carregando").css("display", "none")
            $("#descricao_servico").val($dados.valores['item_descricao']);
            $("#servico_id").val($dados.valores['servico_id']);
            $("#item_id_servico").val($dados.valores['item_id']);
            $("#quantidade_servico_item").val($dados.valores['quantidade_orcada']);
            $("#valor_unitario_servico_item").val($dados.valores['valor_unitario']);
            $("#valor_total_servico_item").val($dados.valores['valor_total']);
            $("#responsavel").val($dados.valores['responsavel']);

            $("#parceiro_terceirizado").val($dados.valores['parceiro_terceirizado_id']);
            $("#data_inicio_terceirizado").val($dados.valores['data_inicio_terceirizado']);
            $("#data_fim_terceirizado").val($dados.valores['data_fim_terceirizado']);
            $("#valor_fechado_terceirizado").val($dados.valores['valor_fechado_terceirizado']);
            $("#descricao_terceirizado_terceirizado").val($dados.valores['descricao_servico_terceirizado']);

            // $(".title .sub-title-material").html("Alterar Servico")
            // $("#button_form_peca").html("Alterar")
            $("#modal_produto").css('display', 'none')
        }
    }

    function falha() {
        console.log("erro");
        $(".modal-body #carregando").css("display", "none")
    }
}

function consultar_tabela_equipe(id) {
    $.ajax({
        type: 'GET',
        data: "equipe_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_equipe_servico.php",
        success: function (result) {
            return $("#equipe-fields").html(result);

        },
    });
}