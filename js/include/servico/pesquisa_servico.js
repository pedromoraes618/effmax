$("#pesquisar_servico").click(function () {
    var conteudo_pesquisa_servico = document.getElementById('pesquisa_conteudo_servico')

    if (conteudo_pesquisa_servico.value == "") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {

        $.ajax({
            type: 'GET',
            data: "consultar_lista_servico=detalhado&conteudo_pesquisa=" + conteudo_pesquisa_servico.value + "&status=sn",
            url: "view/include/servico/table/consultar_servico.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
            },
        });
    }
});


$(".selecionar_servico").click(function () {
    var id = $(this).attr("id")
    var descricao = $("#descricao_" + id).val()
    var valor_unitario = $(this).attr("valor_unitario")

    $('#descricao_servico').val(descricao)
    $('#servico_id').val(id)
    $('#valor_unitario_servico_item').val(valor_unitario)
    $('#valor_total_servico_item').val(valor_unitario)

    if ($('#quantidade_servico_item').val() == "") {//verificar se tem algum valor já informado no input
        $('#quantidade_servico_item').val('1')
    }
   
    $("#modal_pesquisa_servico").modal('hide');
})