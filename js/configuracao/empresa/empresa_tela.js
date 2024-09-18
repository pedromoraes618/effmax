
show() // funcao para retornar os dados para o formulario
$("#registro_empresa").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja alterar essas informações?",
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


})

function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_registro_empresa=true&acao=update&" + dados.serialize(),
        url: "modal/configuracao/empresa/gerenciar_empresa.php",
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


//mostrar as informações no formulario show
function show() {
    $.ajax({
        type: "POST",
        data: "formulario_registro_empresa=true&acao=show",
        url: "modal/configuracao/empresa/gerenciar_empresa.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#rzaosocial").val($dados.valores['rzaosocial'])
            $("#nfantasia").val($dados.valores['nfantasia'])
            $("#cnpjcpf").val($dados.valores['cnpjcpf'])
            $("#ie").val($dados.valores['ie'])
            $("#cep").val($dados.valores['cep'])
            $("#endereco").val($dados.valores['endereco'])
            $("#bairro").val($dados.valores['bairro'])
            $("#estado").val($dados.valores['estado'])
            $("#cidade").val($dados.valores['cidade'])
            $("#telefone").val($dados.valores['telefone'])
            $("#email").val($dados.valores['email'])
            $("#numero").val($dados.valores['numero'])
            $("#regime_tributario").val($dados.valores['regime_tributario'])
            $("#inscricao_municipal").val($dados.valores['inscricao_municipal'])
            $("#cnae").val($dados.valores['cnae'])


            if ($dados.valores['ambiente_focusnfe'] == "1") {//ambiente homologacao
                $('#flexRadiofocusnfeHomologacao').attr('checked', true);
            } else if ($dados.valores['ambiente_focusnfe'] == "2") {
                $('#flexRadiofocusnfeProducao').attr('checked', true);
            }


            $("#token_homologacao_focusnfe").val($dados.valores['token_homologacao_focusnfe'])
            $("#token_producao_focusnfe").val($dados.valores['token_producao_focusnfe'])

        }
    }

    function falha() {
        console.log("erro");
    }

}



$("#consutar_cnpj").click(function () {

    var cnpj = document.getElementById('cnpjcpf').value

    if (cnpj == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "Informe o campo cnpj",
            timer: 7500,

        })
    } else {
        document.getElementById("carregando").style.display = "block";
        $.ajax({
            'url': 'https://www.receitaws.com.br/v1/cnpj/' + cnpj.replace(/[^0-9]/g, '', ".", "-"),
            'type': "GET",
            'dataType': 'jsonp',
            'success': function (data) {

                if (data.nome == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: "Cnpj não encontrado, favor verifique",
                        timer: 7500,

                    })
                    document.getElementById("carregando").style.display = "none";
                } else {

                    document.getElementById('cnae').value = data.atividade_principal['0'].code;
                    document.getElementById('rzaosocial').value = data.nome;
                    document.getElementById('nfantasia').value = data.fantasia;
                    document.getElementById('cep').value = data.cep;
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;

                    document.getElementById('telefone').value = data.telefone;
                    document.getElementById('email').value = data.email
                    document.getElementById('estado').value = data.uf
                    $("#buscar_cep").trigger('click'); // clicar automaticamente para realizar a consulta de localidade

                    document.getElementById("carregando").style.display = "none";
                }
            }
        })
    }
})



$("#buscar_cep").click(function () {
    var cep = document.getElementById("cep").value

    if (cep == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "Favor informe o CEP",
            timer: 7500,
        });
    } else {
        document.getElementById("carregando").style.display = "block";
        var cep_replace = cep.replace(/[^0-9]/g, '', ".", "-");

        const url = `https://viacep.com.br/ws/${cep_replace}/json/`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.erro) { // Verifica se a propriedade "erro" está presente no objeto JSON
                    Swal.fire({
                        icon: 'error',
                        title: 'CEP não encontrado',
                        text: 'O CEP informado não foi encontrado. Verifique e tente novamente.',
                    });
                } else {

                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
                document.getElementById("carregando").style.display = "none";
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: 'O CEP informado não foi encontrado. Verifique e tente novamente.',
                });
                document.getElementById("carregando").style.display = "none";
            });
    }
});

$('#upload_logo').click(function (e) {
    e.preventDefault()

    $('#logo_empresa').ajaxForm({
        url: "modal/configuracao/empresa/upload_img.php",
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
                setTimeout(function () {
                    location.reload(true); // true força o recarregamento do cache
                }, 1000);
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



//modal para consultar os anexo
$(".modal_anexo").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_anexo=true&form_id=" + 1 + "&tipo=empresa",
        url: "view/include/anexo/consultar_anexo.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_anexo").modal('show');

        },
    });
});
