
show_pagamento() // funcao para retornar os dados para o formulario

//mostrar as informações no formulario show
function show_pagamento() {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=show_integracao",
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#frete_gratis").val($dados.valores['frete_gratis']);
            if (($dados.valores['ambiente_mercado_pago']) == "1" || ($dados.valores['ambiente_mercado_pago']) == "2") {
                $('#flexSwitchCheckCheckedFpgMercadoPago').attr('checked', true);
            }

            if ($dados.valores['ambiente_mercado_pago'] == "1") {//ambiente homologacao
                $('#flexRadioMercadoPagoHomologacao').attr('checked', true);
            } else if ($dados.valores['ambiente_mercado_pago'] == "2") {
                $('#flexRadioMercadoPagoProducao').attr('checked', true);
            }

            $('#token_homologacao_mercado_pago').val($dados.valores['token_homologacao_mercado_pago']);
            $('#token_producao_mercado_pago').val($dados.valores['token_producao_mercado_pago']);




            if (($dados.valores['ambiente_paypal']) == "1" || ($dados.valores['ambiente_paypal']) == "2") {
                $('#flexSwitchCheckCheckedFpgPaypal').attr('checked', true);
            }
            if ($dados.valores['ambiente_paypal'] == "1") {//ambiente homologacao
                $('#flexRadioPayPalHomologacao').attr('checked', true);
            } else if ($dados.valores['ambiente_paypal'] == "2") {
                $('#flexRadioPayPalProducao').attr('checked', true);
            }
            $('#token_homologacao_pay_pal').val($dados.valores['token_homologacao_paypal']);
            $('#token_producao_pay_pal').val($dados.valores['token_producao_paypal']);
            $('#email_paypal').val($dados.valores['email_paypal']);

            if (($dados.valores['api_frete']) == "kangu") {
                $('#flexSwitchCheckCheckedFreteKangu').attr('checked', true);
            }
            $('#token_producao_kangu').val($dados.valores['token_producao_kangu']);


            if (($dados.valores['api_meta']) == "S") {
                $('#flexSwitchCheckCheckedApiMeta').attr('checked', true);
                $('.span_conf_api_meta').html($dados.valores['span_conf_api_meta'])

            }
            $('#token_producao_api_meta').val($dados.valores['token_producao_api_meta']);
            $('#datasetid_api_meta').val($dados.valores['datasetid_api_meta']);

          
        }
    }

    function falha() {
        console.log("erro");
    }

}


$("#pagamento").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas configurações.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = integracao(formulario, "update_integracao_pagamento")
        }
    })
})

$("#frete").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas configurações.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = integracao(formulario, "update_integracao_frete")
        }
    })
})

$("#conversao").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas configurações.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = integracao(formulario, "update_integracao_conversao")
        }
    })
})

function integracao(dados, acao) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=" + acao + "&" + dados.serialize(),
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
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




$(document).ready(function () {



    $('input[name="flexSwitchCheckCheckedFpgMercadoPago"]').change(function () {
        if ($('#flexSwitchCheckCheckedFpgMercadoPago').is(':checked')) {
            $('.span_fpg_mercado_pago').show();
        } else {
            $('.span_fpg_mercado_pago').hide();
        }
    });

    // Inicializar a visibilidade com base no estado atual dos rádios
    if ($('#flexSwitchCheckCheckedFpgMercadoPago').is(':checked')) {
        $('.span_fpg_mercado_pago').show();
    } else {
        $('.span_fpg_mercado_pago').hide();
    }

    /*Integracao mercado pago*/
    $('input[name="flexRadioDefaultMercadoPago"]').change(function () {
        if ($('#flexRadioMercadoPagoHomologacao').is(':checked')) {
            $('.span_homologacao_mercado_pago').show();
            $('.span_producao_mercado_pago').hide();
        } else if ($('#flexRadioMercadoPagoProducao').is(':checked')) {
            $('.span_producao_mercado_pago').show();
            $('.span_homologacao_mercado_pago').hide();
        }
    });


    // Inicializar a visibilidade com base no estado atual dos rádios
    if ($('#flexRadioMercadoPagoHomologacao').is(':checked')) {
        $('.span_homologacao_mercado_pago').show();
        $('.span_producao_mercado_pago').hide();
    } else if ($('#flexRadioMercadoPagoProducao').is(':checked')) {
        $('.span_producao_mercado_pago').show();
        $('.span_homologacao_mercado_pago').hide();
    } else {
        $('.span_homologacao_mercado_pago, .span_producao_mercado_pago').hide();
    }

    /*Integracao PayPal*/
    // Inicializar a visibilidade com base no estado atual dos rádios
    if ($('#flexSwitchCheckCheckedFpgPaypal').is(':checked')) {
        $('.span_fpg_pay_pal').show();
    } else {
        $('.span_fpg_pay_pal').hide();
    }

    $('input[name="flexSwitchCheckCheckedFpgPaypal"]').change(function () {
        if ($('#flexSwitchCheckCheckedFpgPaypal').is(':checked')) {
            $('.span_fpg_pay_pal').show();
        } else {
            $('.span_fpg_pay_pal').hide();
        }
    });



    // Inicializar a visibilidade com base no estado atual dos rádios
    if ($('#flexRadioPayPalHomologacao').is(':checked')) {
        $('.span_homologacao_pay_pal').show();
        $('.span_producao_pay_pal').hide();
    } else if ($('#flexRadioPayPalProducao').is(':checked')) {
        $('.span_producao_pay_pal').show();
        $('.span_homologacao_pay_pal').hide();
    } else {
        $('.span_homologacao_pay_pal').hide();
    }


    $('input[name="flexRadioDefaultPayPal"]').change(function () {
        if ($('#flexRadioPayPalHomologacao').is(':checked')) {
            $('.span_homologacao_pay_pal').show();
            $('.span_producao_pay_pal').hide();
        } else if ($('#flexRadioPayPalProducao').is(':checked')) {
            $('.span_producao_pay_pal').show();
            $('.span_homologacao_pay_pal').hide();
        }
    });


    /*integracao kangu - frete */
    // Inicializar a visibilidade com base no estado atual dos rádios
    if ($('#flexSwitchCheckCheckedFreteKangu').is(':checked')) {
        $('.span_frete_kangu').show();
    } else {
        $('.span_frete_kangu').hide();
    }

    $('input[name="flexSwitchCheckCheckedFrete"]').change(function () {
        if ($('#flexSwitchCheckCheckedFreteKangu').is(':checked')) {
            $('.span_frete_kangu').show();
        } else {
            $('.span_frete_kangu').hide();
        }
    });

    /*integracao conversão  */
    // Inicializar a visibilidade com base no estado atual dos rádios
    if ($('#flexSwitchCheckCheckedApiMeta').is(':checked')) {
        $('.span_api_meta').show();
    } else {
        $('.span_api_meta').hide();
    }

    $('input[name="flexSwitchCheckCheckedApiMeta"]').change(function () {
        if ($('#flexSwitchCheckCheckedApiMeta').is(':checked')) {
            $('.span_api_meta').show();
        } else {
            $('.span_api_meta').hide();
        }
    });


});