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
    $('#quantidade').val('1')
    $('#desconto').val('0')
    $('#referencia').val(referencia)
    $("#modal_pesquisa_produto_delivery").modal('hide');
})