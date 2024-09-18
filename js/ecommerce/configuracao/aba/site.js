
show_site() // funcao para retornar os dados para o formulario

//mostrar as informações no formulario show
function show_site() {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=show_site",
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#facebook").val($dados.valores['facebook']);
            $("#instagram").val($dados.valores['instagram']);
            $("#tiktok").val($dados.valores['tiktok']);
            $("#whatsapp").val($dados.valores['whatsapp']);
            $("#nome_site").val($dados.valores['nome_site']);
            $("#sobre_nos .ql-editor").html($dados.valores['sobre_nos']);
            $("#apresentacao .ql-editor").html($dados.valores['apresentacao']);

        }
    }

    function falha() {
        console.log("erro");
    }

}



//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");
var status_cupom = document.getElementById("status_cupom");

$("#adicionar_cupom").click(function () {

    $.ajax({
        type: 'GET',
        data: "cupom_tela=true",
        url: "view/ecommerce/configuracao/cupom_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_cupoom").modal('show');

        },
    });
})


//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_cupom=inicial&conteudo_pesquisa=" + conteudo_pesquisa.value +
        "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value,
    url: "view/ecommerce/configuracao/table/consultar_cupom.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela_cupom").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa

    $.ajax({
        type: 'GET',
        data: "consultar_cupom=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value +
            "&data_inicial=" + data_inicial.value + "&data_final=" + data_final.value + "&status_cupom=" + status_cupom.value,
        url: "view/ecommerce/configuracao/table/consultar_cupom.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela_cupom").html(result);
        },
    });
})



$("#site").submit(function (e) {
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
            var retorno = update_site(formulario, "update_site")
        }
    })
})

$("#redes_sociais").submit(function (e) {
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
            var retorno = update_site(formulario, "update_redes_sociais")
        }
    })
})
$("#sobre").submit(function (e) {
    e.preventDefault()
    var sobre_nos = $("#sobre_nos .ql-editor").html()
    var apresentacao = $("#apresentacao .ql-editor").html()
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
            var retorno = update_sobre("update_sobre", sobre_nos, apresentacao)
        }
    })
})
$("#forma_pagamento").submit(function (e) {
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
            var retorno = update_site(formulario, "update_forma_pagamento",)
        }
    })
})
function update_sobre(acao, sobre_nos, apresentacao) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=" + acao + "&sobre_nos=" + sobre_nos + "&apresentacao=" + apresentacao,
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

function update_site(dados, acao) {
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


$("#file-input").change(function () {
    $('#logo_empresa').ajaxForm({
        url: "modal/ecommerce/configuracao/upload_img.php",
        type: 'POST',
        success: function (data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: $dados.title,
                    showConfirmButton: false,
                    timer: 3500
                })
                $("#aba_site").trigger('click')
                //f5 do tecldo
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.title,
                    timer: 7500,

                })
            }
        }
    }).submit();

});

