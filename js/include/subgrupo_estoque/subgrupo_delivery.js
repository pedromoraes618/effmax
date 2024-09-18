
var img_subgrupo = $('#img_subgrupo_estoque').val()

$('.bg-img-subgrupo-estoque').attr({//adicionar a imagem na div
    'style': 'background-image: url("img/subgrupo_estoque/' + img_subgrupo + '")', // alterar a img na div
});


$("#open_upload_img_subgrupo_estoque").click(function () {
    $.ajax({
        type: 'GET',
        data: "upload_img_subgrupo_estoque=true",
        url: "view/include/subgrupo_estoque/subgrupo_upload_img.php",
        success: function (result) {
            return $(".modal_externo_modal").html(result) + $("#modal_upload_subgrupo_estoque_img").modal('show');

        },
    });
})

