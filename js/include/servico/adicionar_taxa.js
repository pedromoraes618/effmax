const formulario_post = document.getElementById("incluir_taxa");
consultar_tabela_registro_ordem_servico(form_id.value)

//formulario para cadastro
$("#incluir_taxa").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (form_id.value != '') {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja abater esse valor?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                incluir_taxa_ordem_servico(formulario, form_id.value)
            }
        })
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "Ordem de serviço não encontrada!",
            timer: 7500,

        })
    }
})

function incluir_taxa_ordem_servico(dados, id) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=incluir_taxa&form_id=" + id + "&" + dados.serialize(),
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
            formulario_post.reset(); // redefine os valores do formulário
            consultar_tabela_registro_ordem_servico(id)//atualizar tabela de registros
            show_valores(id)
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



function consultar_tabela_registro_ordem_servico(id) {
    $.ajax({
        type: 'GET',
        data: "registro_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_registros_ordem_servico.php",
        success: function (result) {
            return $(".tabela_taxa").html(result);

        },
    });
}





$(".recibo_taxa").click(function () {
    var janela = "view/venda/venda_mercadoria/recibo/recibo_quitacao.php?recibo_quitacao=true&acao=os_taxa&os_id=" + form_id.value
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
})


$(".recibo_adiantamento").click(function () {
    var janela = "view/venda/venda_mercadoria/recibo/recibo_quitacao.php?recibo_quitacao=true&acao=os_adiantamento&os_id=" + form_id.value
    window.open(janela, 'popuppage',
        'width=1500,toolbar=0,resizable=1,scrollbars=yes,height=800,top=100,left=100');
})


// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });
$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_taxa_ordem_servico'), // Para garantir que funcione no modal
        width: '100%',

    });
});