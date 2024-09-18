
var id_nf_fiscal = document.getElementById("nf_id")
var codigo_nf_fiscal = document.getElementById("codigo_nf")
var serie_nf = document.getElementById("serie_nf")
show_nf(id_nf_fiscal.value, codigo_nf_fiscal.value)
show_valores(codigo_nf_fiscal.value)
tabela_produtos_nf(codigo_nf_fiscal.value);

$(".btn-close-modal").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal
})


//mostrar as informações no formulario show
function show_nf(id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=show&nf_id=" + id + "&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#numero_nf").val($dados.valores['numero_nf'])
            $("#serie_nf").val($dados.valores['serie_nf'])
            $("#chave_acesso").val($dados.valores['chave_acesso'])
            $("#protocolo").val($dados.valores['protocolo'])
            $("#parceiro_descricao").val($dados.valores['parceiro_descricao'])
            $("#consultar_pdf_nf").attr("href", $dados.valores['pdf_nf']);
            $("#consultar_xml_nf").attr("href", $dados.valores['caminho_xml_nf']);

            $("#consultar_carta_correcao_nf").attr("href", $dados.valores['carta_correcao_nf']);

            if ($dados.valores['serie_nf'] == 'NFC') {
                $('#acao_crt').remove()
                $('#inutilizar_nf').remove()
                $('#preview_nf').remove()
            }
            if ($dados.valores['serie_nf'] == 'NFS') {
                $('#acao_crt').remove()
                $('#inutilizar_nf').remove()
                $('#preview_nf').remove()
                $('#cpf_nota').remove()
            }

            if ($dados.valores['serie_nf'] == 'NFE') {
                $('#cpf_nota').remove()
            }
        }
    }

    function falha() {
        console.log("erro");
    }

}


//informa aos campos os valores de imposto resumidamente no corpo da nota
function show_valores(codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=resumo_valores&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            $("#valor_bruto").val($dados.valores['valor_bruto'])
            $("#valor_liquido").val($dados.valores['valor_liquido'])
            $("#desconto").val($dados.valores['desconto'])
        }
    }

    function falha() {
        console.log("erro");
    }
}

$("#enviar_nf").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja enviar essa nota?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            modal_enviar_nf(id_nf_fiscal.value)
        }
    })
})
$("#preview_nf").click(function () {
    var numero_nf = $("#numero_nf").val()
    modal_preview_nf(id_nf_fiscal.value, numero_nf)

})

$("#inutilizar_nf").click(function () {
    var numero_nf = $("#numero_nf").val()
    modal_inutilizar_nf_nf(id_nf_fiscal.value, numero_nf)

})
$("#crt_correcao").click(function () {
    var numero_nf = $("#numero_nf").val()
    modal_carta_correcao_nf(id_nf_fiscal.value, numero_nf)

})
$("#cancelar_nf").click(function () {
    var numero_nf = $("#numero_nf").val()
    modal_cancelar_nf(id_nf_fiscal.value, numero_nf)

})
$("#cpf_nota").click(function () {
    var numero_nf = $("#numero_nf").val()
    modal_cpf_nota(id_nf_fiscal.value, numero_nf)
})

function modal_enviar_nf(id) {
    $.ajax({
        type: 'GET',
        data: "nf_saida=true&acao=enviar_nf&id_nf=" + id,
        url: "view/include/nf_fiscal/enviar_nf.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_consulta_nf").modal('show')

        },
    });
}
function modal_preview_nf(id) {
    $.ajax({
        type: 'GET',
        data: "nf_saida=true&acao=enviar_nf&id_nf=" + id,
        url: "view/include/nf_fiscal/preview_nf.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_consulta_nf").modal('show')

        },
    });
}

function modal_cpf_nota(id) {
    $.ajax({
        type: 'GET',
        data: "nf_saida=true&acao=adicionar_cpf_nota&id_nf=" + id,
        url: "view/include/nf_fiscal/cpf_nota.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_cpf_nota").modal('show')

        },
    });
}


function modal_cancelar_nf(id) {
    $.ajax({
        type: 'GET',
        data: "nf_saida=true&acao=enviar_nf&id_nf=" + id,
        url: "view/include/nf_fiscal/cancelar_nf.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_consulta_nf").modal('show')

        },
    });

}

function modal_carta_correcao_nf(id) {
    $.ajax({
        type: 'GET',
        data: "nf_saida=true&acao=carta_correcao&id_nf=" + id,
        url: "view/include/nf_fiscal/carta_correcao_nf.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_consulta_nf").modal('show')

        },
    });

}
function modal_inutilizar_nf_nf(id, numero_nf) {
    $.ajax({
        type: 'GET',
        data: "nf_saida=true&acao=enviar_nf&id_nf=" + id + '&numero_nf=' + numero_nf,
        url: "view/include/nf_fiscal/inutilizar_nf.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_consulta_nf").modal('show')

        },
    });

}

function tabela_produtos_nf(codigo_nf) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "tabela_produto=true&codigo_nf=" + codigo_nf + "&obs=preview",
        url: "view/venda/nf_saida/table/tabela_produtos.php",
        success: function (result) {
            return $(".tabela_externa").html(result)

        },
    })
}


function inserir_cpf_cnpj_avulso_nf(cpf_cnpj_avulso, id) {
    $.ajax({
        type: "POST",
        data: "nf_saida=true&acao=inserir_cpf_cnpj_avulso_nf&cpf_cnpj=" + cpf_cnpj_avulso + "&nf=" + id,
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
