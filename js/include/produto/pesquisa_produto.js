var conteudo_pesquisa_produto = document.getElementById('pesquisa_conteudo_produto')
var tipo_produto = document.getElementById('tipo_produto')
var operacao_produto = document.getElementById('operacao_produto')
$("#pesquisar_produto").click(function () {

    if (conteudo_pesquisa_produto.value == "") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_produto=detalhado&conteudo_pesquisa=" + conteudo_pesquisa_produto.value + "&tipo_produto=" + tipo_produto.value + "&operacao_produto=" + operacao_produto.value,
            url: "view/include/produto/table/consultar_produto.php",
            success: function (result) {
                return $("#modal_pesquisa_produto .tabela").html(result);
            },
        });
    }
});


$(".selecionar_produto").click(function () {
    var id_produto = $(this).attr("id_produto")
    var preco_venda = $(this).attr("preco_venda")
    var unidade = $(this).attr("unidade")
    var estoque = $(this).attr("estoque")
    var descricao_produto = $('#' + id_produto).val()
    var referencia = $("input[referencia_" + id_produto + "]").val();//PEGANDO O VALOR DO INPUT QUE TEM O ATRIBUTO REFERENCIA


    $('#produto_id').val(id_produto)
    $('#descricao_produto').val(descricao_produto)
    $('#preco_venda').val(preco_venda)
    $('#preco_venda_atual').val(preco_venda)
    $('#valor_total').val(preco_venda)
    $('#unidade').val(unidade)

    $('#estoque').val(estoque)
    if ($('#quantidade').val() == "") {//verificar se tem algum valor já informado no input
        $('#quantidade').val('1')
    }
    $('#desconto').val('0')
    $('#referencia').val(referencia)
    $("#modal_pesquisa_produto").modal('hide');
})