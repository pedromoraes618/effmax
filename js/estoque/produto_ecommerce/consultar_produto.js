//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo")
var status_prod = document.getElementById("status_prod")
var tipo_produto = document.getElementById("tipo_produto")
var subgrupo = document.getElementById("subgrupo")
var estoque_consulta = document.getElementById("estoque_consulta")
var status_promocao = document.getElementById("status_promocao")
var unidade_medida = document.getElementById("unidade_medida")

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_produto_ecommerce=inicial",
    url: "view/estoque/produto_ecommerce/table/consultar_produto.php",
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
                consultar_produto_ecommerce: 'detalhado',
                conteudo_pesquisa: conteudo_pesquisa.value,
                status_prod: status_prod.value,
                tipo_produto: tipo_produto.value,
                subgrupo: subgrupo.value,
                estoque_consulta: estoque_consulta.value,
                status_promocao: status_promocao.value,
                unidade_medida: unidade_medida.value,
            },
            url: 'view/estoque/produto_ecommerce/table/consultar_produto.php',
            success: function (result) {
                $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
            },
            error: function (xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }
})


$("#adicionar_produto").click(function () {
    /*abrir modal */
    $.ajax({
        type: 'GET',
        data: "tela_produto=true",
        url: "view/estoque/produto_ecommerce/produto_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_produto").modal('show');;

        },
    });
})



// $(document).ready(function () {
//     // Inicializar o Chosen no select com a classe 'chosen-select'
//     $('.chosen-select').chosen({
//         width: '100%' // Para garantir que o Chosen ocupe toda a largura do contêiner do Bootstrap

//     });
// });

$(document).ready(function () {
    $('.select2').select2({
        dropdownAutoWidth: true,
        width: '100%',
    });
});