

//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
var status_prod = document.getElementById("status_prod")
var tipo_produto = document.getElementById("tipo_produto_consulta_individual")
var subgrupo = document.getElementById("subgrupo")
var estoque_consulta = document.getElementById("estoque_consulta")
var unidade_medida = document.getElementById("unidade_medida")

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_produto=inicial",
    url: "view/estoque/ajuste_estoque1/table/consultar_produto.php",
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
                estoque_consulta: estoque_consulta.value,
                unidade_medida: unidade_medida.value
            },
            url: "view/estoque/ajuste_estoque1/table/consultar_produto.php",
            success: function (result) {
                $(".bloco-pesquisa-menu .bloco-pesquisa-1 .layout .tabela").html(result);
            },
            error: function (xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }
})


$('#salvar_ajuste').click(function () {
    $('#ajuste_estoque_item').submit();
});

$("#ajuste_estoque_item").submit(function (e) {
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
            var retorno = ajuste_item(formulario, trCount)
        }
    })
})

function ajuste_item(dados, qtdRegistros) {


    $.ajax({
        type: "POST",
        data: "formulario_ajuste_estoque=true&acao=create&" + dados.serialize() + "&qt_registro=" + qtdRegistros,
        url: "modal/estoque/ajuste_estoque1/gerenciar_ajuste_estoque.php",
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