

show_layout() // funcao para retornar os dados para o formulario

//mostrar as informações no formulario show
function show_layout() {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=show_layout",
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {

            if (($dados.valores['baner_topo_status']) == "S") {
                $('#baner_topo_status').attr('checked', true);
            }
            if (($dados.valores['baner_topo_cupom_status']) == "S") {
                $('#baner_topo_cupom_status').attr('checked', true);
            }


            if (($dados.valores['secao_novidade_status']) == "S") {
                $('#secao_novidade_status').attr('checked', true);
            }
            $("#titulo_secao_novidade").val($dados.valores['titulo_secao_novidade']);

            if (($dados.valores['secao_desconto_status']) == "S") {
                $('#secao_desconto_status').attr('checked', true);
            }
            $("#titulo_secao_desconto").val($dados.valores['titulo_secao_desconto']);

            if (($dados.valores['secao_destaque_status']) == "S") {
                $('#secao_destaque_status').attr('checked', true);
            }
            $("#titulo_secao_destaque").val($dados.valores['titulo_secao_destaque']);

            if (($dados.valores['secao_catalogo_status']) == "S") {
                $('#secao_catalogo_status').attr('checked', true);
            }
            $("#titulo_secao_catalogo").val($dados.valores['titulo_secao_catalogo']);

            if (($dados.valores['secao_inscreva_se_status']) == "S") {
                $('#secao_inscreva_se_status').attr('checked', true);
            }
            $("#titulo_secao_inscreva_se").val($dados.valores['titulo_secao_inscreva_se']);
            $(".text-secao-inscreva_se").html($dados.valores['titulo_secao_inscreva_se']);

            $("#limite_produto_secao").val($dados.valores['limite_produto_secao']);
            $("#limite_produto_pagina").val($dados.valores['limite_produto_pagina']);



            if (($dados.valores['status_baner_secao']) == "S") {
                $('#status_baner_secao').attr('checked', true);
            }


            if (($dados.valores['status_mais_buscados']) == "S") {
                $('#secao_mais_buscado_status').attr('checked', true);
            }
            $("#titulo_secao_prd_mais_buscado").val($dados.valores['titulo_mais_buscados']);


        }
    }

    function falha() {
        console.log("erro");
    }
}


$("#file-input-baner-topo").change(function () {
    $('#baner_topo_img').ajaxForm({
        url: "modal/ecommerce/configuracao/upload_img.php",
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
                $("#aba_layout").trigger('click')
                //f5 do tecldo
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


$(".file-input-baner-section").change(function () {
    var section = $(this).data("section"); // Corrigido de 'data-id' para 'id'
    $('#baner_section_' + section).ajaxForm({
        url: "modal/ecommerce/configuracao/upload_img.php",
        type: 'POST',
        data: { section: section }, // Corrigido para passar dados no formato de objeto
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
                $("#aba_layout").trigger('click')
                //f5 do tecldo
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




$("#baner_topo_status").click(function () {
    var formulario = $("#baner_topo");
    formulario.trigger('submit'); // Ativa a submissão do formulário
});
$("#status_baner_secao").click(function () {
    var formulario = $("#status_baner_secao");
    formulario.trigger('submit'); // Ativa a submissão do formulário
});

$("#baner_topo_cupom_status").click(function () {
    var formulario = $("#baner_topo_cupom");
    formulario.trigger('submit'); // Ativa a submissão do formulário
});

/*envio do formualario via onchange*/
$("#baner_topo").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    update_layout(formulario, "update_baner_topo")
})

$("#baner_topo_cupom").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    update_layout(formulario, "update_baner_topo_cupom")
})

$("#baner_secao").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    update_layout(formulario, "update_status_baner_secao")
})

$("#secao_site").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    update_layout(formulario, "update_secao_site")
})
// /*envio do formualario via onchange*/
// $(".status_baner_secao").click(function () {
//     var status_baner_secao = $(this).val()
//     alert(status_baner_secao)
// })



$(".excluir_baner_topo").click(function () {
    var banerId = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse baner?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_baner(banerId, "delete_baner_topo")
        }
    })
});
$(".excluir_baner_secao").click(function () {
    var banerId = $(this).attr("id")

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse baner?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_baner(banerId, "delete_baner_secao")
        }
    })
});




function update_layout(dados, acao) {
    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=" + acao + "&" + dados.serialize(),
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 3500
            })
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


function delete_baner(id, acao) {

    $.ajax({
        type: "POST",
        data: "formulario_configuracao_ecommerce=true&acao=" + acao + "&id=" + id,
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 3500
            })
            $("#aba_layout").trigger('click')

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
    $(".draggable-baner-topo").draggable({
        revert: true
    });

    $(".group-baner").droppable({
        drop: function (event, ui) {
            var dropped = ui.draggable;
            var droppedOn = $(this).find(".draggable-baner-topo").filter(function () {
                return $(this).offset().top + $(this).height() / 2 > dropped.offset().top + dropped.height() / 2;
            }).first();
            if (droppedOn.length) {
                dropped.insertBefore(droppedOn);
            } else {
                dropped.appendTo($(this));
            }

            // Atualizar IDs e posições de todas as divs
            var divsArray = [];
            $(".group-baner .draggable-baner-topo").each(function (index) {
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
        data: "formulario_configuracao_ecommerce=true&acao=ordernar_baner_topo&dados=" + JSON.stringify(divsArray),
        url: "modal/ecommerce/configuracao/gerenciar_configuracao.php",
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