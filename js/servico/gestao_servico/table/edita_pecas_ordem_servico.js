let form_id = document.getElementById("id")

//abrir a pagina de edição do formulario, pegando o id 
$(".editar_peca").click(function () {
    var id = $(this).attr("id")
    show_det_peca(id)
    $('#modal_pecas_ordem_servico').scrollTop(0); // quando clicado em editar o scroll vai para a caixa de edição;

})

$(".remover_material").click(function () {
    var id = $(this).attr("id")
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse material?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            remover_peca(id, form_id.value)
        }
    })
})


function remover_peca(id, form_id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=remover_peca&item_id=" + id,
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
            consultar_tabela_material(form_id)
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


function consultar_tabela_material(id) {

    $.ajax({
        type: 'GET',
        data: "material_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_materiais_ordem_servico.php",
        success: function (result) {
            return $(".tabela_material").html(result);

        },
    });
}


//mostrar as informações no formulario show
function show_det_peca(id) {
    $(".modal-body #carregando").css("display", "block")

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=show_det_peca&id=" + id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $(".modal-body #carregando").css("display", "none")
            $("#descricao_produto").val($dados.valores['item_descricao']);
            $("#produto_id").val($dados.valores['produto_id']);
            $("#item_id_material").val($dados.valores['item_id']);
            $("#unidade").val($dados.valores['unidade']);
            $("#quantidade").val($dados.valores['quantidade_orcada']);
            $("#preco_venda").val($dados.valores['valor_unitario']);
            $("#valor_total").val($dados.valores['valor_total']);

            // $("#servico_id").val($dados.valores['servico_destinado_id']);
            $('#servico_id option[value="' + $dados.valores['servico_destinado_id'] + '"]').prop('selected', true).trigger('change');

            // $("#tipo_material").val($dados.valores['tipo_material_id']);
            $('#tipo_material option[value="' + $dados.valores['tipo_material_id'] + '"]').prop('selected', true).trigger('change');


            $(".title .sub-title-material").html("Alterar Material")
            // $("#button_form_peca").html("Alterar")
            $("#modal_produto").css('display', 'none')
        }
    }

    function falha() {
        console.log("erro");
        $(".modal-body #carregando").css("display", "none")

    }

}