
const formulario_post = document.getElementById("baixa_parcial");
let id_lancamento = document.getElementById("id_lancamento")
let id_baixa = document.getElementById("id_baixa")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')


show(id_lancamento.value) // funcao para retornar os dados para o formulario
tabela_baixa_parcial(id_lancamento.value)

//formulario para cadastro
$("#baixa_parcial").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (id_baixa.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar essa baixa parcial?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario, id_lancamento.value)
            }
        })
    } else {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar essa baixa parcial?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = update(formulario)
            }
        })
    }

})

function create(dados, id) {
    $.ajax({
        type: "POST",
        data: "formulario_baixa_duplicata=true&acao=create_baixa_parcial&" + dados.serialize(),
        url: "modal/financeiro/baixa_duplicata/gerenciar_baixa_duplicata.php",
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
            tabela_baixa_parcial(id)
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
            $('#valor_bx_parcial').val('')
            $('#forma_pagamento').val('0')
            $('#conta_financeira').val('0')
            $('#observacao').val('')


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





//mostrar as informações no formulario show
function show(id) {

    $.ajax({
        type: "POST",
        data: "formulario_baixa_duplicata=true&acao=show&form_id=" + id,
        url: "modal/financeiro/baixa_duplicata/gerenciar_baixa_duplicata.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#valor_liquido").val($dados.valores['valor_liquido'])
            $("#doc").val($dados.valores['doc'])
            $("#valor_liquido_hidden").val($dados.valores['valor_liquido'])
        }
    }

    function falha() {
        console.log("erro");
    }

}


function tabela_baixa_parcial(id) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "tabela_baixa_parcial=true&form_id=" + id,
        url: "view/financeiro/baixa_duplicata/table/consultar_baixa_parcial.php",
        success: function (result) {
            return $(".tabela_bx_parcial").html(result)

        },
    })
}
//calulcar o valor liquido do lancamento
function calcula_v_liquido_bx_parcial() {
    var valor_bx_parcial = $('#valor_bx_parcial').val();
    var valor_liquido = $('#valor_liquido_hidden').val();


    if (valor_bx_parcial) {//verificando se tem um virgula e substituindo pelo ponto, apos isso e transformado para numero(parsefloat)
        if (valor_bx_parcial.includes(",")) {
            valor_bx_parcial = valor_bx_parcial.replace(",", ".");
        }
        valor_bx_parcial = parseFloat(valor_bx_parcial)
        valorFinal = valor_liquido - valor_bx_parcial;
    }


    valorFinal = (valorFinal.toFixed(2));
    $('#valor_liquido').val(valorFinal);

}