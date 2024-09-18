function show_all_imagens(codigo_nf) {
    $.ajax({
        type: 'GET',
        data: {
            consultar_all_imagem: true,
            codigo_nf: codigo_nf
        },
        url: "view/include/produto/table/consultar_all_imagens_ecommerce.php",
        success: function (result) {
            $(".bloco-pesquisa-menu .bloco-pesquisa-1 .all_imagens").html(result);
            attachClickHandlers(); // Adiciona os handlers de clique após carregar as imagens
        },
        error: function (xhr, status, error) {
            // Lógica para lidar com erros de solicitação aqui
        }
    });
}

function attachClickHandlers() {
    $(".imagem-container").click(function () {
        var ordem = $(this).attr("id");
        var codigo_nf = $("#codigo_nf").val(); // Pega o valor do input com id "codigo_nf"
        $.ajax({
            type: 'GET',
            data: {
                upload_img: true,
                ordem: ordem,
                codigo_nf: codigo_nf
            },
            url: "view/include/produto/produto_upload_img_ecommerce.php",
            success: function (result) {
                $(".all_imagens .upload-img").html(result);
                $("#modal_upload_produto_img").modal('show');
            },
        });
    });

    $(".icone-remover").click(function () {
        var imagem = $(this).attr("id");
        var codigo_nf = $("#codigo_nf").val(); // Pega o valor do input com id "codigo_nf"
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
                delete_img(imagem, codigo_nf);
            }
        });
    });
}

function delete_img(imagem, codigo_nf) {
    $.ajax({
        type: "POST",
        data: {
            formulario_produto_ecommerce: true,
            acao: 'delete_img',
            imagem: imagem
        },
        url: "modal/estoque/produto/gerenciar_produto.php",
        success: function (data) {
            var $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {
                show_all_imagens(codigo_nf);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $dados.title,
                    timer: 7500,
                });
            }
        },
        error: function () {
            console.log("erro");
        }
    });
}

// Chamada inicial para carregar as imagens
show_all_imagens($("#codigo_nf").val()); // Chama a função com o valor inicial do input com id "codigo_nf"
