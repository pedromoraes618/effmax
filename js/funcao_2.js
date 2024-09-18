//calulcar o valor liquido do produto
function calcular_valor_total_item() {
    
    var quantidade = $('#quantidade_item').val();
    var preco_venda = $('#preco_venda_item').val();
    // var preco_venda_atual = $('#preco_venda_atual').val();

    if (quantidade) {//verificando se tem um virgula e substituindo pelo ponto, apos isso e transformado para numero(parsefloat)
        if (quantidade.includes(",")) {
            quantidade = quantidade.replace(",", ".");
        }
        quantidade = parseFloat(quantidade)
    }

    if (preco_venda) {
        if (preco_venda.includes(",")) {
            preco_venda = preco_venda.replace(",", ".");
        }
        preco_venda = parseFloat(preco_venda)
    }


    if (quantidade && preco_venda) {
        var valorFinal = (quantidade * preco_venda);
        valorFinal = (valorFinal.toFixed(2));

        $('#valor_total_item').val(valorFinal);
    }



}


//calulcar o valor liquido do produto
function calcular_desconto_item() {
    var preco_venda = $('#preco_venda_item').val();
    var preco_venda_atual = $('#preco_venda_item_atual').val();
 
    if (preco_venda != preco_venda_atual) {//verificar se o valor do preco de venda foi alterado
        if (preco_venda) {
            if (preco_venda.includes(",")) {
                preco_venda = preco_venda.replace(",", ".");
            }
            preco_venda = parseFloat(preco_venda)
        }

        if (preco_venda) {

            valor_final = ((preco_venda * 100) / preco_venda_atual)
            valor_final = (100 - valor_final)
            valor_final = (valor_final.toFixed(2))
            $('#desconto_item').val(valor_final)
        }

    }else{
        $('#desconto_item').val(0)

    }

}

//calulcar o valor liquido do produto
function calcular_preco_venda_item() {
    var desconto = $('#desconto_item').val();
    var preco_venda_atual = $('#preco_venda_item_atual').val();

    if (desconto) {//verificar se o valor do preco de venda foi alterado
        if (desconto) {
            if (desconto.includes(",")) {
                desconto = desconto.replace(",", ".");
            }
            desconto = parseFloat(desconto)
        }
        valor_final = preco_venda_atual - (desconto / 100) * preco_venda_atual;
        valor_final = (valor_final.toFixed(2));
        $('#preco_venda_item').val(valor_final);
    }

}