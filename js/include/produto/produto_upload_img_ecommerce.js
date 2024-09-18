var codigo_nf = document.getElementById("codigo_nf")
$('#upload_img').click(function (e) {
    e.preventDefault()
    $('#upload_img_produto').ajaxForm({
        url: "modal/estoque/produto/upload_img_ecommerce.php",
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
                show_all_imagens(codigo_nf.value)
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.title,
                    timer: 7500,

                })
            }
        }
    }).submit();

});
