var codigo_nf = $("#codigo_nf").val()
var form_id = $("#form_id").val()
var tipo = $("#tipo").val()

$('#adicionar_arquivo').click(function (e) {
    e.preventDefault()

    $('#upload_anexo').ajaxForm({
        url: "modal/anexo/upload.php",
        type: 'POST',
        data: {
            form_id: form_id,
            codigo_nf: codigo_nf,
            tipo: tipo
        },
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
                $("#fechar_modal_upload_anexo").trigger('click')
                $("#pesquisar_filtro_pesquisa_anexo").trigger('click')
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
})
