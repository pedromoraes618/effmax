



const formulario_post = document.getElementById("produto");
let id_formulario = document.getElementById("id")
let btn_form = document.getElementById('button_form')
let codigo_nf = document.getElementById('codigo_nf')


function generateUniqueId() {
    var timestamp = Date.now().toString(); // Obter o timestamp atual como uma string
    var randomNum = Math.random().toString().substr(2, 5); // Gerar um número aleatório e extrair uma parte dele
    var uniqueId = timestamp + randomNum; // Concatenar o timestamp e o número aleatório
    return uniqueId;
}

//retorna os dados para o formulario
if (id_formulario.value == "") {
    $(".title .sub-title").html("Cadastro de produto")//alterar a label cabeçalho
    $("#button_form").html("Cadastrar")
    $("#codigo_nf").val(generateUniqueId())//gerar codigo aleatorio
} else {
    $(".title .sub-title").html("Alterar produto")
    $('#button_form').html('Salvar');
    show(id_formulario.value) // funcao para retornar os dados para o formulario
    preco_venda_sugerido()//calcular preco de venda sugerido
}
consultar_marcadores(codigo_nf.value)

$("#clonar").click(function () {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja clonar esse produto?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = clonar(id_formulario.value)
        }
    })
})

$("#produto").submit(function (e) {//adicionar o produto na venda
    e.preventDefault()
    var formulario = $(this);
    var descricao_completa = $("#descricao_completa .ql-editor").html()

    if (id_formulario.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja cadastrar esse produto?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario, descricao_completa)
            }
        })
    } else {//editar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse produto?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = update(formulario, descricao_completa)
            }
        })
    }

})


$(".tabela_preco").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_tabela_preco=true&id_item=" + id_formulario.value,
        url: "view/include/produto/tabela_preco.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_externo").html(result) + $("#modal_tabela_preco").modal('show')
        },
    });
})

$(".btn_imagem").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_imagem_produto=true&codigo_nf=" + codigo_nf.value,
        url: "view/include/produto/produto_ecommerce.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_externo").html(result) + $("#modal_consulta_imagem_produto").modal('show')
        },
    });
})

//modal para consultar os anexo
$(".modal_anexo").click(function () {
    if (id_formulario.value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "É necesssario adicionar o produto",
            timer: 7500,
        })
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_anexo=true&form_id=" + id_formulario.value + "&tipo=produto",
            url: "view/include/anexo/consultar_anexo.php",
            success: function (result) {
                return $(".modal_externo").html(result) + $("#modal_anexo").modal('show');

            },
        });
    }
});

//mostrar as informações no formulario show
function show(id) {

    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=show&produto_id=" + id,
        url: "modal/estoque/produto/gerenciar_produto.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#codigo_nf").val($dados.valores['codigo_nf'])
            $("#descricao").val($dados.valores['descricao'])
            $("#referencia").val($dados.valores['referencia'])
            //$("#equivalencia").val($dados.valores['equivalencia'])
            $("#codigo_barras").val($dados.valores['codigo_barras'])
            $("#grupo_estoque").val($dados.valores['grupo_id'])
            $("#fabricante").val($dados.valores['fabricante'])
            // $("#tipo").val($dados.valores['tipo'])
            $("#estoque").val($dados.valores['estoque'])
            //$("#est_minimo").val($dados.valores['est_minimo'])
            //$("#est_maximo").val($dados.valores['est_maximo'])
            //$("#local_produto").val($dados.valores['local'])
            //$("#tamanho").val($dados.valores['tamanho'])
            // $("#unidade_md").val($dados.valores['und'])
            // $("#status").val($dados.valores['status_ativo'])
            $("#prc_venda").val($dados.valores['preco_venda'])
            $("#prc_custo").val($dados.valores['preco_custo'])
            $("#margem_lucro").val($dados.valores['margem'])
            $("#prc_promocao").val($dados.valores['preco_promocao'])
            //$("#desconto_maximo").val($dados.valores['desconto_maximo'])
            //$("#ult_preco_compra").val($dados.valores['ult_preco_compra'])
            $("#peso_produto").val($dados.valores['peso_produto'])
            $("#cest").val($dados.valores['cest'])
            $("#ncm").val($dados.valores['ncm'])
            $("#cst_icms").val($dados.valores['cst_icms'])
            $("#cst_pis_s").val($dados.valores['pis_s'])
            $("#cst_pis_e").val($dados.valores['pis_e'])
            $("#cst_cofins_s").val($dados.valores['cofins_s'])
            $("#cst_cofins_e").val($dados.valores['cofins_e'])
            $("#observacao").val($dados.valores['observacao'])
            $("#data_valida_promocao").val($dados.valores['data_valida_promocao'])
            $("#unidade_md").val($dados.valores['und_id'])
            $("#tipoProduto").val($dados.valores['tipoProduto'])
            // $("#data_validade").val($dados.valores['data_validade'])
            //$("#img_produto").val($dados.valores['img_produto'])
            //  $("#descricao_completa .ql-edito").html($dados.valores['descricao_completa'])
            $("#descricao_completa .ql-editor").html($dados.valores['descricao_completa'])

            if (($dados.valores['lancamento']) == "SIM") {
                $('#lancamento').attr('checked', true);
            } else {
                $('#lancamento').attr('checked', false);
            }

            if (($dados.valores['condicao']) == "NOVO") {
                $('#condicao_novo').attr('checked', true);
            } else {
                $('#condicao_usado').attr('checked', true);
            }


            if (($dados.valores['tipo_ecommerce']) == "NACIONAL") {
                $('#tipo_nacional').attr('checked', true);
            } else {
                $('#tipo_internacional').attr('checked', true);
            }

            if (($dados.valores['status_ativo']) == "SIM") {
                $('#status').attr('checked', true);
            } else {
                $('#status').attr('checked', false);

            }

            if (($dados.valores['destaque']) == "SIM") {
                $('#destaque').attr('checked', true);
            } else {
                $('#destaque').attr('checked', false);
            }
            if (($dados.valores['fixo']) == "SIM") {
                $('#fixo').attr('checked', true);
            } else {
                $('#fixo').attr('checked', false);
            }
        }
    }

    function falha() {
        console.log("erro");
    }

}


function create(dados, descricao_completa) {
    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=create&descricao_completa=" + descricao_completa + "&" + dados.serialize(),
        url: "modal/estoque/produto/gerenciar_produto.php",
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
            // $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
            $("#descricao_completa .ql-editor").html('')//limpar o campo descrição
            $("#codigo_nf").val(generateUniqueId())//gerar codigo aleatorio

            show_img_produtos($("#codigo_nf").val())
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



function update(dados, descricao_completa) {
    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=update&descricao_completa=" + descricao_completa + "&" + dados.serialize(),
        url: "modal/estoque/produto/gerenciar_produto.php",
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
            // $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
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


function clonar(form_id) {
    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=clonar&form_id=" + form_id,
        url: "modal/estoque/produto/gerenciar_produto.php",
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


$("#adicionar_marcador").click(function () {
    var descricao_marcador = $("#descricao_marcador").val()
    var codigo_nf_marcador = $("#codigo_nf").val()
    if (descricao_marcador != "") {
        create_marcador(codigo_nf_marcador, descricao_marcador)
    }
})
function create_marcador(codigo_nf, descricao) {
    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=create_marcador&descricao=" + descricao + "&codigo_nf=" + codigo_nf,
        url: "modal/estoque/produto/gerenciar_produto.php",
        async: false
    }).then(sucesso, falha);
    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#descricao_marcador").val('')//resetar o valor da descrição do marcador
            consultar_marcadores(codigo_nf)
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

function consultar_marcadores(codigo_nf) {
    $.ajax({
        type: 'GET',
        data: "consultar_marcadores=true&codigo_nf=" + codigo_nf,
        url: "view/include/produto/table/consultar_marcadores.php",
        success: function (result) {
            return $(".marcadores").html(result)
        },
    });
}

function show_img_produtos(codigo_nf) {
    $.ajax({
        type: 'GET',
        data: "img_produtos=true&codigo_nf=" + codigo_nf,
        url: "view/include/produto/imagens_produto.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .imagem-produto").html(result)
        },
    });
}
function consultar_variacao(codigo_nf) {
    $.ajax({
        type: 'GET',
        data: "consultar_variacao=true&codigo_nf=" + codigo_nf,
        url: "view/include/produto/table/consultar_variacao.php",
        success: function (result) {
            return $(".box-tabela-variantes").html(result)
        },
    });
}

show_img_produtos(codigo_nf.value)
consultar_variacao(codigo_nf.value)

function preco_venda_sugerido() {
    var margem_lucro = document.getElementById("margem_lucro").value
    var preco_custo = document.getElementById("prc_custo").value
    //transformar em float
    margem_lucro = parseFloat(margem_lucro)
    preco_custo = parseFloat(preco_custo)

    var preco_venda = (preco_custo / (1 - (margem_lucro / 100))).toFixed(2);

    var prc_venda = document.getElementById("prc_venda_sug")
    prc_venda.value = preco_venda
}


//funcionalidade subgrupo
$("#grupo_estoque").change(function () {
    var grupo_estoque = document.getElementById("grupo_estoque")

    //funcionalidade para trazer as informações do subgrupo para os campos automaticamente
    $.ajax({
        type: "POST",
        data: "subgrupo_selecionado=true&id_subgrupo=" + grupo_estoque.value,
        url: "modal/estoque/produto/consultar_grupo.php",
        async: false
    }).then(sucesso, falha);
    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];

        if (($dados.sucesso)) {//Preencher informações do grupo nos campos dos produtos automaticamente
            $("#estoque").val($dados.estoque_inicial)
            $("#ncm").val($dados.ncm)
            $("#cst_icms").val($dados.cst_icms)
            $("#cst_pis_s").val($dados.cst_pis_s)
            $("#cst_pis_e").val($dados.cst_pis_e)
            $("#cst_cofins_s").val($dados.cst_cofins_s)
            $("#cst_cofins_e").val($dados.cst_cofins_e)
            $("#unidade_md").val($dados.unidade)
        }
    }

    function falha() {
        console.log("erro grupo selecao")
    }
})

$('#modal_produto').on('hidden.bs.modal', function () {
    $(".modal-backdrop").remove();
});


$(".btn-closer").click(function () {
    $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta

})

$("#modal_ncm").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_ncm=true",
        url: "view/estoque/produto/include/modal_cest_ncm.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_externo").html(result) + $("#modal_cunsultar_cest_ncm").modal('show')
        },
    });

})
$("#modal_cest").click(function () {

    $.ajax({
        type: 'GET',
        data: "consultar_cest=true",
        url: "view/estoque/produto/include/modal_cest_ncm.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_externo").html(result) + $("#modal_cunsultar_cest_ncm").modal('show')
        },
    });


})

$(".opcao_produto").click(function () {
    $.ajax({
        type: 'GET',
        data: "opcao_produto=true&codigo_nf=" + codigo_nf.value,
        url: "view/include/produto/opcao_produto.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_opcao_produto").modal('show');

        },
    });
})


const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });
$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_produto'), // Para garantir que funcione no modal
        width: '100%',

    });
});