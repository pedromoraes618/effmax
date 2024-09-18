$("#file-input-img-produto").change(function () {
    $('#upload_img_produto').ajaxForm({

        url: "modal/estoque/produto/upload_img_new.php",
        type: 'POST',
        data: {
            codigo_nf: codigo_nf.value // Adiciona o parâmetro codigo_nf aos dados enviados
        },
        success: function (data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.status == true) {
                show_img_produtos(codigo_nf.value)
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


$(".excluir_img_produto").click(function () {
    var prodId = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover essa imagem?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_img_produto(prodId)
        }
    })
});

function delete_img_produto(prodId) {

    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=delete_img_produto&id=" + prodId,
        url: "modal/estoque/produto/gerenciar_produto.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            show_img_produtos(codigo_nf.value)
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.title,
                timer: 7500,

            })
        }
    }

    function falha() {
        console.log("erro");
    }
}



$(document).ready(function () {
    $(".draggable-produto").draggable({
        revert: true
    });

    $(".group-produto").droppable({
        drop: function (event, ui) {
            var dropped = ui.draggable;
            var droppedOn = $(this).find(".draggable-produto").filter(function () {
                return $(this).offset().top + $(this).height() / 2 > dropped.offset().top + dropped.height() / 2;
            }).first();
            if (droppedOn.length) {
                dropped.insertBefore(droppedOn);
            } else {
                dropped.appendTo($(this));
            }

            // Atualizar IDs e posições de todas as divs
            var divsArray = [];
            $(".group-produto .draggable-produto").each(function (index) {
                var id = $(this).attr('id');
                divsArray.push({
                    id: id,
                    position: index
                });
            });

            // Mostrar array no console
            // console.log(divsArray);

            // Chamar a função ordernar imediatamente após a inserção da div
            // var id = dropped.attr('id');
            // var newPosition = dropped.index();
            ordernar(divsArray);
        }
    });
});

function ordernar(divsArray) {
    $.ajax({
        type: "POST",
        data: "formulario_produto_ecommerce=true&acao=ordernar_imagem_produto&dados=" + JSON.stringify(divsArray),
        url: "modal/estoque/produto/gerenciar_produto.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso != true) {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.title,
                timer: 7500,
            })
        }
    }

    function falha() {
        console.log("erro");
    }
}