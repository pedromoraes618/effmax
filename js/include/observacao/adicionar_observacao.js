
$(document).ready(function(){//ao abrir o modal se já tiver alguma observação no imput do formulario, será informado no campo observacao no modal
   var observacao = $('#observacao').val()
    $('#valor_observacao').val(observacao);
})

$("#salvar_observacao").click(function () {//adicionar observação no imput do formulario
    var valor_observacao = $('#valor_observacao').val();
    if (valor_observacao != "") {
        $('#observacao').val(valor_observacao)
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: "Observação incluida com sucesso",
            showConfirmButton: false,
            timer: 3500
        })
    }

})