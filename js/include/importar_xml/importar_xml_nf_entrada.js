
$('#upload_img').click(function () {
    $('#upload_img_user').ajaxForm({
        url: "modal/compra/compra_mercadoria/importar_xml_nf_entrada.php",
        type: 'POST',
        success: function (data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {


                var nf_id = $dados.title['nf_id']
                var codigo_nf = $dados.title['codigo_nf']

                $.ajax({
                    type: 'GET',
                    data: "editar_nf_entrada=true&form_id=" + nf_id + "&codigo_nf=" + codigo_nf,
                    url: "view/compra/compra_mercadoria/compra_tela.php",
                    success: function (result) {
                        return $(".modal_atalho").html(result) + $("#modal_compra").modal('show');

                    },
                });

               
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.title,
                    timer: 8500,
                })
            
            }
        }
    }).submit();
});
//modal para consultar o parceiro
$("#modal_cadastrar_parceiro").click(function () {

    $.ajax({
        type: 'GET',
        data: "cadastrar_parceiro=true",
        url: "view/include/cadastrar_parceiro/parceiro_tela.php",
        success: function (result) {
            return $(".modal_atalho").html(result) + $("#modal_tela_cadastrar_parceiro").modal('show');

        },
    });
});

