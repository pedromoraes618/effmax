

//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
var status_prod = document.getElementById("status_prod")
var tipo_produto = document.getElementById("tipo_produto_consulta_individual")
var subgrupo = document.getElementById("subgrupo")
var estoque_consulta = document.getElementById("estoque_consulta")

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_produto=inicial",
    url: "view/estoque/ajuste_preco/table/consultar_produto_lote.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});



$("#pesquisar_filtro_pesquisa").click(function () {
    if (conteudo_pesquisa.value == "" && status_prod.value == "0") {
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
        setTimeout(function () {
            $(".alerta .alert").css("display", "none")
        }, 5000);
    } else {
        $.ajax({
            type: 'GET',
            data: {
                consultar_produto: 'detalhado',
                conteudo_pesquisa: conteudo_pesquisa.value,
                status_prod: status_prod.value,
                tipo_produto: tipo_produto.value,
                subgrupo: subgrupo.value,
                estoque_consulta: estoque_consulta.value
            },
            url: "view/estoque/ajuste_preco/table/consultar_produto_lote.php",
            success: function (result) {
                $(".bloco-pesquisa-menu .bloco-pesquisa-1 .layout .tabela").html(result);
            },
            error: function (xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }
})

// Manipulador de evento para o checkbox pai
$("#selecionarAllCheck").on('change', function () {
    // Verifica se o checkbox pai está marcado ou desmarcado
    var isChecked = $(this).is(':checked');
    // Marca ou desmarca todos os checkboxes filhos com base no estado do checkbox pai
    $(".form-check-input").prop('checked', isChecked);
});

// Manipulador de evento para os checkboxes filhos para atualizar o estado do checkbox pai
$(".form-check-input").on('change', function () {
    // Verifica se todos os checkboxes filhos estão marcados
    var allChecked = $(".form-check-input").length === $(".form-check-input:checked").length;
    // Atualiza o estado do checkbox pai
    $("#selecionarAllCheck").prop('checked', allChecked);
});


$('#salvar_ajuste').click(function () {
    $('#ajuste_preco_lote').submit();
});

$("#ajuste_preco_lote").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    var trCount = $('#produtos-tbody tr').length;

    //e.preventDefault()
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja realizar esse ajuste ?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = ajuste_lote(formulario, trCount)
        }
    })
})

function ajuste_lote(dados, qtdRegistros) {

    $.ajax({
        type: "POST",
        data: "formulario_ajuste_preco=true&acao=create_lote&" + dados.serialize() + "&qt_registro=" + qtdRegistros,
        url: "modal/estoque/ajuste_preco/gerenciar_ajuste_preco.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
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
    // Inicializar o Chosen no select com a classe 'chosen-select'
    $('.chosen-select').chosen({
        width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

    });
});