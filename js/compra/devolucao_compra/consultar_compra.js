
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var status_recebimento = 0;


$(document).ready(function () {
    //consultar tabela
    $.ajax({
        type: 'GET',
        data: "consultar_devolucao_compra=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value + "&data_inicial=" + data_inicial.value +
         "&data_final=" + data_final.value,
        url: "view/compra/devolucao_compra/table/consultar_compra.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_devolucao_compra=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value + 
        "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value + "&status_recebimento=" + status_recebimento,
        url: "view/compra/devolucao_compra/table/consultar_compra.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})




$('#cfop_gerar').select2();


const cfop_gerar = document.getElementById("cfop_gerar")
$('#gerar_nfe').click(function () {

    var selectedValues = [];//array de nf selecionadas
    $('.check_gerar_doc:checked').each(function () {
        selectedValues.push($(this).val());
    });

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja gerar essa(s) nota(s) para a serie NFE?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            gerar_nf(selectedValues, cfop_gerar.value, 'NFE')
        }
    })
});
$('#gerar_nfc').click(function () {

    var selectedValues = [];//array de nf selecionadas
    $('.check_gerar_doc:checked').each(function () {
        selectedValues.push($(this).val());
    });


    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja gerar essa(s) nota(s) para a serie NFC?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            gerar_nf(selectedValues, cfop_gerar.value, 'NFC')
        }
    })
});

function gerar_nf(dados, cfop, serie) {

    let dadosJSON = JSON.stringify(dados); //codificar para json
    $.ajax({
        type: "POST",
        data: "nf_saida=true&acao=gerar_nf&serie_nf=" + serie + "&cfop=" + cfop + "&nf=" + dadosJSON,
        url: "modal/venda/nf_saida/gerenciar_nf_saida.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal

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
    $('.select2').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('body') // Ou o contêiner específico onde está o select
    });
});