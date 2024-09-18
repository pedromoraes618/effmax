var conteudo_pesquisa_parceiro = document.getElementById('pesquisa_conteudo_parceiro')
$("#pesquisar_parceiro").click(function () {
   
    if (conteudo_pesquisa_parceiro.value == "") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {
        $.ajax({
            type: 'GET',
            data: "consultar_cliente=detalhado&conteudo_pesquisa=" + conteudo_pesquisa_parceiro.value,
            url: "view/include/parceiro/table/consultar_parceiro.php",
            success: function (result) {
                return $("#modal_pesquisa_parceiro .tabela").html(result);
            },
        });
    }
});


$(".selecionar_parceiro").click(function(){
   
    var id_parceiro = $(this).attr("id_parceiro")
    var r_social = $('#'+id_parceiro).val()
    $('#parceiro_descricao').val(r_social )
    $('#parceiro_id').val(id_parceiro)
    $("#modal_pesquisa_parceiro").modal('hide');
})