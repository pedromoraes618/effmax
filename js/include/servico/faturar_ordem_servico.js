
// //valores do campo de pesquisa //pesquisa via filtro

// $("#adicionar_ordem_servico").click(function () {

//     $.ajax({
//         type: 'GET',
//         data: "ordem_servico=true",
//         url: "view/servico/ordem_servico/ordem_servico_tela.php",
//         success: function (result) {
//             return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_ordem_servico").modal('show');

//         },
//     });
// })


// //consultar tabela
// $.ajax({
//     type: 'GET',
//     data: "consultar_ordem_servico=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value +
//         "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
//     url: "view/servico/ordem_servico/table/consultar_ordem_servico.php",
//     success: function (result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
//     },
// });
consulta_os_pendente()

$("#pesquisar_filtro_pesquisa_os_pendente").click(function () {//realizar a pesquisa
    consulta_os_pendente()
})

function consulta_os_pendente() {
    var conteudo_pesquisa_os_pendente = document.getElementById("pesquisa_conteudo_os_pendente");
    var data_inicial_os_pendente = document.getElementById("data_inicial_os_pendente");
    var data_final_os_pendente = document.getElementById("data_final_os_pendente");


    $.ajax({
        type: 'GET',
        data: "consultar_ordem_servico=detalhado&conteudo_pesquisa=" + conteudo_pesquisa_os_pendente.value +
            "&data_inicial=" + data_inicial_os_pendente.value + "&data_final=" + data_final_os_pendente.value + "&form_id=" + form_id.value,
        url: "view/servico/ordem_servico/table/consultar_ordem_servico_pendente.php",
        success: function (result) {
            return $(".tabela_os_pendentes").html(result);
        },
    });
}


//formulario para cadastro
$("#faturar").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (form_id.value != "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja faturar essa ordem de serviço? ",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var osPendenteArray = [];
                $('input[id="os_pendente"]:checked').each(function () {
                    osPendenteArray.push($(this).val());
                });
                osPendenteArray.push(form_id.value);
                var retorno = faturar(formulario, form_id.value, osPendenteArray)
            }
        })
    }
})

function faturar(dados, form_id, osPendenteArray) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=faturar&form_id=" + form_id + "&os_pendente=" + JSON.stringify(osPendenteArray) + "&" + dados.serialize(),
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            // console.log($dados.title)
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 6500
            })
            $('.modal-backdrop').remove();
            $(".btn-close-valores").trigger('click')
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

$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_faturar_ordem_servico'), // Para garantir que funcione no modal
        width: '100%',

    });
});