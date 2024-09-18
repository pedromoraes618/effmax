function generateUniqueId() {
    var timestamp = Date.now().toString(); // Obter o timestamp atual como uma string
    var randomNum = Math.random().toString().substr(2, 5); // Gerar um número aleatório e extrair uma parte dele
    var uniqueId = timestamp + randomNum; // Concatenar o timestamp e o número aleatório
    return uniqueId;
}



if ($("#codigo_nf").val() == "") {//veriricar se está vazio se sim, será gerado um unicoid
    $("#codigo_nf").val(generateUniqueId())
} else {
    //remover botão de finalizar e adicionar ajuste
    $("#finalizar_ajuste").remove()
    $("#adicionar_produto_ajuste").remove()
    $('.info-prod').remove()
}

var codigo_nf = $("#codigo_nf").val()
tabela_produtos(codigo_nf)  //consultar tabela de ajst
localStorage.clear();      // Limpa o localStorage ao carregar a página

$('#fechar_modal_ajst_estoque').click(function () {
    setTimeout(function () {
        $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar fechar o modal
    }, 100);
})

//modal para consultar o produto
$("#modal_produto").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_produto=true",
        url: "view/include/produto/pesquisa_produto.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_produto").modal('show');

        },
    });
});

/*funcões */
function resetarValoresProdutos() {
    $("#produto_id").val('')
    $("#descricao_produto").val('')
    $("#unidade").val('')
    $("#estoque").val('')
    $("#qtd_ajuste").val('')
    $("#tipo").val('0')
    $("#preco_venda_atual").val('')
    $("#data_validade").val('')
}


// var itens = [];

function add_produto() {
    var descricao = $('#descricao_produto').val();
    var produto_id = $('#produto_id').val();
    var estoque = $('#estoque').val();
    var unidade = $('#unidade').val();
    var tipo = $('#tipo').val();
    var qtd_ajuste = $('#qtd_ajuste').val();
    var valor_item = $('#preco_venda_atual').val();
    var data_validade = $('#data_validade').val();
    var motivo = $('#motivo').val();

    var item = {
        descricao: descricao,
        produto_id: produto_id,
        estoque: estoque,
        unidade: unidade,
        tipo: tipo,
        qtd_ajuste: qtd_ajuste,
        valor_item: valor_item,
        data_validade: data_validade,
        motivo: motivo
    }


    // Recupera o array de itens do localStorage ou cria um novo array vazio
    var itensArmazenados = JSON.parse(localStorage.getItem(codigo_nf)) || [];

    // Adiciona o novo item ao array de itens
    itensArmazenados.push(item);

    // Salva o array de itens atualizado no localStorage
    localStorage.setItem(codigo_nf, JSON.stringify(itensArmazenados));

    // Limpar campos ou realizar outras ações aqui
    resetarValoresProdutos();

};




$("#ajuste_estoque").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja adicionar esse ajuste",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = validar_produto(formulario, codigo_nf)
        }
    })
})

function validar_produto(dados, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "fomulario_ajuste_estoque=true&acao=validar_produto&" + dados.serialize(),
        url: "modal/estoque/ajuste_estoque/gerenciar_ajuste_estoque.php",
        async: false
    }).then(sucesso, falha);


    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            // Recupere o valor associado à chave 'codigo_nf' do localStorage
            add_produto();
            var dados_tabela = JSON.parse(localStorage.getItem(codigo_nf) || '[]'); // Recupera os itens do localStorage

            tabela_produtos_localstorage(codigo_nf, dados_tabela);

            // tabela_produtos(codigo_nf)
            //$("#estoque").val($dados.qtd)//atualizar o valor do estoque no campo
            //resetarValoresProdutos()
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




$("#finalizar_ajuste").click(function () {
    var dados_tabela = JSON.parse(localStorage.getItem(codigo_nf) || '[]'); // Recupera os itens do localStorage

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja finalizar esse ajuste",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            finalizar(dados_tabela, codigo_nf)
        }
    })
})




function finalizar(dados_tabela, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "fomulario_ajuste_estoque=true&codigo_nf=" + codigo_nf + "&acao=finalizar_ajuste&itens=" + JSON.stringify(dados_tabela),
        url: "modal/estoque/ajuste_estoque/gerenciar_ajuste_estoque.php",
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
            tabela_produtos(codigo_nf)
            $('#fechar_modal_ajst_estoque').trigger('click'); // clicar automaticamente para realizar fechar o modal
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


function tabela_produtos(codigo_nf) {//tabela de produtos
    $.ajax({
        type: 'GET',
        data: "consultar_ajst_produtos=true&codigo_nf=" + codigo_nf,
        url: "view/estoque/ajuste_estoque/table/tabela_ajst.php",
        success: function (result) {
            return $(".tabela_externa").html(result)
        },
    })
}
function tabela_produtos_localstorage(codigo_nf, dados_tabela) {//tabela de produtos

    $.ajax({
        type: 'GET',
        data: "consultar_ajst_produtos=true&codigo_nf=" + codigo_nf + "&itens=" + JSON.stringify(dados_tabela),
        url: "view/estoque/ajuste_estoque/table/tabela_ajst.php",
        success: function (result) {
            return $(".tabela_externa").html(result)
        },
    })
}