
$('#upload_baner').click(function () {

    $('#baner').ajaxForm({
        url: "modal/delivery/configuracao/upload_baner.php",
        type: 'POST',
        success: function (data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: $dados.title,
                    showConfirmButton: false,
                    timer: 3500
                })
                $('.btn-close').trigger('click'); // clicar automaticamente para realizar fechar o modal
                tabela_baner()
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.valores,
                    timer: 7500,
    
                })
            }
        }
    }).submit();
});
