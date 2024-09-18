const formulario_post = document.getElementById("incluir_despesa");
consultar_tabela_despesa_ordem_servico(form_id.value)

//formulario para cadastro
$("#incluir_despesa").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (form_id.value != '') {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar essa despesa?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                incluir_despesa_ordem_servico(formulario, form_id.value)
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

function incluir_despesa_ordem_servico(dados, id) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=incluir_despesa&form_id=" + id + "&" + dados.serialize(),
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
            consultar_tabela_despesa_ordem_servico(id)//atualizar tabela de registros
            show_valores(id)
            $('option[value="0"]').prop('selected', true).trigger('change');

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



function consultar_tabela_despesa_ordem_servico(id) {
    $.ajax({
        type: 'GET',
        data: "despesa_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_despesas_ordem_servico.php",
        success: function (result) {
            return $(".tabela_taxa").html(result);

        },
    });
}


$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_despesa_ordem_servico'), // Para garantir que funcione no modal
        width: '100%',

    });
});
// //modal para consultar o parceiro
// $("#modal_parceiro_despesa").click(function () {

//     $.ajax({
//         type: 'GET',
//         data: "adicionar_parceiro=true",
//         url: "view/include/parceiro/pesquisa_parceiro.php",
//         success: function (result) {
//             return $(".modal_externo_3").html(result) + $("#modal_pesquisa_parceiro").modal('show');

//         },
//     });
// });

