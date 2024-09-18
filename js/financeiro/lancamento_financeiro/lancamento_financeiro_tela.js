// $("#voltar").click(function (e) {
//     $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta

// })

const formulario_post = document.getElementById("lancamento_financeiro");
let id_formulario = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')
let tipo = document.getElementById('tipo')


//modal para consultar o cliente
$("#modal_parceiro").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_financeiro=true&tipo=RECEITA",
        url: "view/include/parceiro/pesquisa_parceiro.php",
        success: function (result) {
            return $(".modal_parceiro").html(result) + $("#modal_pesquisa_parceiro").modal('show');

        },
    });
});

//retorna os dados para o formulario
if (id_formulario.value == "") {

    $('#button_form').html('<i class="bi bi-check-all"></i> Adicionar');
    if (tipo.value == "RECEITA") {
        $(".title .sub-title").html("Lançar Receita")//alterar a label cabeçalho
    }
    if (tipo.value == "DESPESA") {
        $(".title .sub-title").html("Lançar Despesa")//alterar a label cabeçalho
    }

} else {
    $('#button_form').html('<i class="bi bi-check-all"></i> Alterar');
    $(".title .sub-title").html("Alterar lançamento")

    show(id_formulario.value) // funcao para retornar os dados para o formulario

}

//formulario para cadastro
$("#lancamento_financeiro").submit(function (e) {

    e.preventDefault()
    var formulario = $(this);

    if (id_formulario.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar esse lançamento?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario)
            }
        })
    } else {//editar
        //e.preventDefault()

        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse lançamento",
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

function create(dados) {

    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=create&" + dados.serialize(),
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


            formulario_post.reset(); // redefine os valores do formulário
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


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=update&" + dados.serialize(),
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



//mostrar as informações no formulario show
function show(id) {
    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=show&conta_financeira_id=" + id,
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#data_movimento").val($dados.valores['data_movimento'])
            $("#data_vencimento").val($dados.valores['data_vencimento'])
            $("#data_pagamento").val($dados.valores['data_pagamento'])
            $("#conta_financeira").val($dados.valores['conta_financeira'])
            $("#forma_pagamento").val($dados.valores['forma_pagamento'])

            $("#parceiro_id").val($dados.valores['parceiro_id'])
            $("#parceiro_descricao").val($dados.valores['parceiro_descricao'])

            $("#status").val($dados.valores['status'])
            $("#classificacao").val($dados.valores['classificacao'])
            $("#descricao").val($dados.valores['descricao'])
            $("#documento").val($dados.valores['documento'])
            $("#ordem_servico").val($dados.valores['ordem_servico'])

            $("#valor_bruto").val($dados.valores['valor_bruto'])
            $("#valor_liquido").val($dados.valores['valor_liquido'])
            $("#juros").val($dados.valores['juros'])
            $("#taxa").val($dados.valores['taxa'])
            $("#baixa_parcial").val($dados.valores['baixa_parcial'])
            $("#desconto").val($dados.valores['desconto'])
            $("#observacao").val($dados.valores['observacao'])
            $("#taxa_cartao").val($dados.valores['taxa_cartao'])

            var previsao_juros = $dados.valores['previsao_juros']
            if (previsao_juros == true) {
                $("#juros").addClass("is-invalid")
            }

        }
    }

    function falha() {
        console.log("erro");
    }

}


//calulcar o valor liquido do lancamento
function calcula_v_liquido() {
    var valor_bruto = $('#valor_bruto').val();
    var juros = $('#juros').val();
    var taxa = $('#taxa').val();
    var baixa_parcial = $('#baixa_parcial').val();
    var desconto = $('#desconto').val();
    var taxa_cartao = $('#taxa_cartao').val();

    if (valor_bruto) {//verificando se tem um virgula e substituindo pelo ponto, apos isso e transformado para numero(parsefloat)
        if (valor_bruto.includes(",")) {
            valor_bruto = valor_bruto.replace(",", ".");
        }
        valor_bruto = parseFloat(valor_bruto)
    }
    let valorFinal = valor_bruto;


    if (juros) {
        if (juros.includes(",")) {
            juros = juros.replace(",", ".");
        }
        juros = parseFloat(juros)
        valorFinal = valorFinal + juros;
    }

    if (taxa) {
        if (taxa.includes(",")) {
            taxa = taxa.replace(",", ".");
        }
        taxa = parseFloat(taxa)
        valorFinal = valorFinal + taxa;
    }

    if (baixa_parcial) {
        if (baixa_parcial.includes(",")) {
            baixa_parcial = baixa_parcial.replace(",", ".");
        }
        baixa_parcial = parseFloat(baixa_parcial)
        valorFinal = valorFinal - baixa_parcial;
    }

    if (desconto) {
        if (desconto.includes(",")) {
            desconto = desconto.replace(",", ".");
        }
        desconto = parseFloat(desconto)
        valorFinal = valorFinal - desconto;
    }

    if (taxa_cartao) {
        if (taxa_cartao.includes(",")) {
            taxa_cartao = taxa_cartao.replace(",", ".");
        }
        taxa_cartao = parseFloat(taxa_cartao)
        valorFinal = valorFinal - taxa_cartao;
    }
    valorFinal = (valorFinal.toFixed(2));
    $('#valor_liquido').val(valorFinal);
    $('#valor_liquido_hidden').val(valorFinal);



}


// $('#classificacao').select2({
//     dropdownParent: $('#modal_lancamento_financeiro')
// });

// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });
$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_lancamento_financeiro'), // Para garantir que funcione no modal
        width: '100%',

    });
});
