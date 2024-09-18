var conteudo_parceiro_avulso = document.getElementById('conteudo_parceiro_avulso')
$("#adicionar_parceiro_avulso").click(function () {//adicionar o nome de um cliente ou fornecedor avulso que não tem cadastro
    if (conteudo_parceiro_avulso.value == "") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe o nome do cliente</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {

        $('#parceiro_descricao').val(conteudo_parceiro_avulso.value)
        $('#parceiro_id').val('')
        $("#modal_adiciona_parceiro_avulso").modal('hide');
    }
})

//modal para consultar o parceiro
$("#modal_cadastrar_parceiro").click(function () {
    $.ajax({
        type: 'GET',
        data: "cadastrar_parceiro=true",
        url: "view/include/cadastrar_parceiro/parceiro_tela.php",
        success: function (result) {
            return $(".modal_externo_cadastrar_parceiro").html(result) + $("#modal_tela_cadastrar_parceiro").modal('show');

        },
    });
});


