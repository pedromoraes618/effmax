var conteudo_pesquisa_transportadora = document.getElementById('pesquisa_conteudo_transportadora')
$("#pesquisar_transportadora").click(function () {
   
    if (conteudo_pesquisa_transportadora.value == "") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_cliente=detalhado&conteudo_pesquisa=" + conteudo_pesquisa_transportadora.value,
            url: "view/include/transportadora/table/consultar_transportadora.php",
            success: function (result) {
                return $("#modal_pesquisa_transportadora .tabela").html(result);
            },
        });
    }
});


$(".selecionar_transportadora").click(function(){

    var id_parceiro = $(this).attr("id_parceiro")
    var r_social = $('#'+id_parceiro).val()
    $('#transportadora_descricao').val(r_social )
    $('#transportadora_id').val(id_parceiro)
    $("#modal_pesquisa_transportadora").modal('hide');
})