tabela_baner()
$("#open_upload_baner").click(function () {

    $.ajax({
        type: 'GET',
        data: "upload_img_user=true",
        url: "view/delivery/configuracao/modulo/modal/upload_baner.php",
        success: function (result) {
            return $(".modal_show").html(result) + $("#modal_upload_baner").modal('show');

        },
    });
})


function tabela_baner() {

    $.ajax({
        type: 'GET',
        data: "consultar_baner=inicial",
        url: "view/delivery/configuracao/modulo/tabela/consultar_baner.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela_baner").html(result);
        },
    });

}