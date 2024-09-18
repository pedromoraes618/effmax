//abrir a pagina de edição do formulario, pegando o id 
$(".editar_lancamento_financeiro").click(function () {
    var form_id = $(this).attr("lancamento_financeiro_id")
    var tipo = $(this).attr("tipo")

    if (tipo == "RECEITA") {
        $.ajax({
            type: 'GET',
            data: "editar_lancamento_financeiro=true&tipo=RECEITA&form_id=" + form_id,
            url: "view/financeiro/lancamento_financeiro/lancamento_financeiro_tela.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_lancamento_financeiro").modal('show');
            },
        });
    }
    if (tipo == "DESPESA") {
        $.ajax({
            type: 'GET',
            data: "editar_lancamento_financeiro=true&tipo=DESPESA&form_id=" + form_id,
            url: "view/financeiro/lancamento_financeiro/lancamento_financeiro_tela.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_lancamento_financeiro").modal('show');

            },
        });
    }

});

$(".clonar").click(function () {
    let form_id = $(this).attr("lancamento_financeiro_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja clonar esse lançamento",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = clonar(form_id)
        }
    })
});

$(".remover").click(function () {
    let form_id = $(this).attr("lancamento_financeiro_id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse lançamento",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = remover(form_id)
        }
    })
}); 

function clonar(form_id) {
    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=clonar&form_id=" + form_id,
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
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
function remover(form_id) {
    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=remover&form_id=" + form_id,
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
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

$(".bx_parcial").click(function () {

    var lancamento_financeiro_id = $(this).attr("lancamento_financeiro_id")

    $.ajax({
        type: 'GET',
        data: "baixa_parcial=true&acao=editar&form_id=" + lancamento_financeiro_id,
        url: "view/financeiro/baixa_duplicata/baixa_parcial_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_baixa_parcial").modal('show');

        },
    });

})
