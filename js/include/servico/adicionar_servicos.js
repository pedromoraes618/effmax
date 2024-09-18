const formulario_post = document.getElementById("servico");
consultar_tabela_servico_ordem_servico(form_id.value)

var item_id_servico = document.getElementById("item_id_servico")
//modal para consultar o produto

if (item_id_servico.value == "") {
    $(".title .sub-title").html("Incluir Servico")
    // $("#button_form_peca").html("Adicionar")
} else {
    $(".title .sub-title").html("Alterar Servico")
    // $("#button_form_peca").html("Alterar")
}


$("#modal_servico").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_servico=true",
        url: "view/include/servico/pesquisa_servico.php",
        success: function (result) {
            return $(".modal_externo_3").html(result) + $("#modal_pesquisa_servico").modal('show');

        },
    });
});


$("#servico").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    if (item_id_servico.value == '') {//adicioanr material
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja incluir esse serviço?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                servico_ordem_servico(formulario, form_id.value)
            }
        })
    } else {//alterar material
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse serviço?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                servico_ordem_servico(formulario, form_id.value)
            }
        })
    }
})

function servico_ordem_servico(dados, id) {

    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=servico&form_id=" + id + "&" + dados.serialize(),
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
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
            if ($dados.query == 'update') {
                $(".title .sub-title-material").html("Incluir Servico")
                // $("#button_form_peca").html("Incluir")
                $("#modal_produto").css('display', 'block')
            }

            formulario_post.reset(); // redefine os valores do formulário
            $('#item_id_servico').val('')
            consultar_tabela_servico_ordem_servico(id)//atualiza a tabela de materiais da os
            show_valores(id)//atualiza os valores na tela de resumo de valores
            $('#equipe-fields').html('')
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


function consultar_tabela_servico_ordem_servico(id) {

    $.ajax({
        type: 'GET',
        data: "servico_1_ordem_servico=true&form_id=" + id,
        url: "view/servico/ordem_servico/table/consultar_servicos_ordem_servico.php",
        success: function (result) {
            return $(".tabela_servicos").html(result);

        },
    });



}


$(document).ready(function () {
    // Função para contar quantos campos já existem
    function getLastFieldCount() {
        var lastField = $('[name^="nome_equipe_"]').last();
        if (lastField.length > 0) {
            var lastName = lastField.attr('name');
            var lastIndex = parseInt(lastName.split('_').pop()); // Pegar o número após o último "_"
            return lastIndex;
        }
        return 0; // Se não houver campos, começa em 0
    }

    $('#add-equipe-btn').on('click', function () {
        var count = getLastFieldCount() + 1; // Começar a contar a partir do último campo existente

        // Template dos 6 campos a serem adicionados com contador no atributo "name"
        var equipeFields = `
                <div class="row membro_equipe_${count} mb-2">
                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-0">
                    <button type="button" id="${count}" title='Remover o membro da equipe' class="btn btn-sm remover_membro_equipe"><i class="bi bi-x fs-5"></i></button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="nome_equipe_${count}" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="nome_equipe_${count}" id="nome_equipe_${count}" placeholder="Digite o nome">
                    </div>
                    <div class="col-6 col-md-2 mb-2">
                        <label for="funcao_equipe_${count}" class="form-label">Função:</label>
                        <input type="text" class="form-control" name="funcao_equipe_${count}" id="funcao_equipe_${count}" placeholder="Digite a função">
                    </div>
                    <div class="col-6 col-md-2 mb-2">
                        <label for="matricula_equipe_${count}" class="form-label">Matrícula:</label>
                        <input type="text" class="form-control" name="matricula_equipe_${count}" id="matricula_equipe_${count}" placeholder="Digite a matrícula">
                    </div>
                    <div class="col-6 col-md-2 mb-2">
                        <label for="data_inicio_equipe_${count}" class="form-label">Data Início:</label>
                        <input type="date" class="form-control" name="data_inicio_equipe_${count}" id="data_inicio_equipe_${count}">
                    </div>
                    <div class="col-6 col-md-2 mb-2">
                        <label for="data_final_equipe_${count}" class="form-label">Data Final:</label>
                        <input type="date" class="form-control" name="data_final_equipe_${count}" id="data_final_equipe_${count}">
                    </div>
                    <hr class="mb-2">
                </div>
            `;
        // Adiciona o template dos campos ao div #resource-fields
        $('#equipe-fields').append(equipeFields);

        $(".remover_membro_equipe").click(function () {
            var membro_equipe_id = $(this).attr("id");
            // Usando template literals para selecionar o elemento com base no ID
            $(".membro_equipe_" + membro_equipe_id).remove();
        });
    });

});



//calulcar o valor total do item
function calcular_valor_total() {
    var valor_unitario = $('#valor_unitario_servico_item').val();
    var quantidade = $('#quantidade_servico_item').val();

    if (valor_unitario.includes(",")) {
        valor_unitario = valor_unitario.replace(",", ".");
    }
    valor_unitario = parseFloat(valor_unitario)

    if (quantidade.includes(",")) {
        quantidade = quantidade.replace(",", ".");
    }
    quantidade = parseFloat(quantidade)

    valor_total = (valor_unitario * quantidade).toFixed(2);
    $('#valor_total_servico_item').val(valor_total);
}


$(document).ready(function () {
    $('.select2-modal-modal').select2({
        dropdownParent: $('#modal_servicos_ordem_servico'), // Para garantir que funcione no modal
        width: '100%', // Mantém o preenchimento do contêiner

    });
});


// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });