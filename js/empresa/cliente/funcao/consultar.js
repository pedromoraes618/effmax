//consultar o cest e informar no campo
$(document).ready(function () {
    $(".consulta_cest").css("display", "none")
    $(".consulta_ncm").css("display", "none")
})

$("#buscar_cest").click(function () {
    var consulta = document.getElementById("pesquisa_conteudo_cest").value
    $.ajax({
        type: 'GET',
        data: "consultar_cest=true&conteudo_pesquisa=" + consulta,
        url: "view/estoque/produto/table/consultar_cest.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .consulta_cest").html(result);
        },
    });

    function mostrarElemento() {
        $(".consulta_cest").css("display", "block")
    }

    setTimeout(mostrarElemento, 1000);

})


$(".selecionar_cest").click(function () {
    var cest = $(this).attr("valor")
    var input_cest = document.getElementById("cest")
    input_cest.value = cest
    $('.btn-close').trigger('click'); // clicar automaticamente
})

//pesquisar ncm e informar no campo
$("#buscar_ncm").click(function () {
    var consulta = document.getElementById("pesquisa_conteudo_ncm").value
    $.ajax({
        type: 'GET',
        data: "consultar_cest=true&conteudo_pesquisa=" + consulta,
        url: "view/estoque/produto/table/consultar_ncm.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .consulta_ncm").html(result);
        },
    });

    function mostrarElemento() {
        $(".consulta_ncm").css("display", "block")
    }

    setTimeout(mostrarElemento, 1000);

})


$(".selecionar_ncm").click(function () {
    var ncm = $(this).attr("valor")
    var input_ncm = document.getElementById("ncm")
    input_ncm.value = ncm
    $('.btn-close').trigger('click');
})



//calcular  margem de lucro
function maregm_lucro() {
    var preco_venda = document.getElementById("prc_venda").value
    var preco_custo = document.getElementById("prc_custo").value
    //transformar em float
    preco_venda = parseFloat(preco_venda)
    preco_custo = parseFloat(preco_custo)

    var maregm_lucro = (((preco_venda - preco_custo) / preco_venda) * 100).toFixed(2);

    var margem = document.getElementById("margem_lucro")
    margem.value = maregm_lucro
}

//calcular preco de venda
function preco_venda() {
    var margem_lucro = document.getElementById("margem_lucro").value
    var preco_custo = document.getElementById("prc_custo").value
    //transformar em float
    margem_lucro = parseFloat(margem_lucro)
    preco_custo = parseFloat(preco_custo)

    var preco_venda = (preco_custo / (1 - (margem_lucro / 100))).toFixed(2);

    var prc_venda = document.getElementById("prc_venda")
    prc_venda.value = preco_venda
}







var grupo_estoque = document.getElementById("grupo_estoque")
var campo_estoque = document.getElementById("estoque")
var campo_estoque_minimo = document.getElementById("est_minimo")
var campo_estoque_maximo = document.getElementById("est_maximo")
var campo_local = document.getElementById("local_produto")
var campo_unidade_md = document.getElementById("unidade_md")
var campo_cfop_interno = document.getElementById("cfop_interno")
var campo_cfop_externo = document.getElementById("cfop_externo")

//funcionalidade subgrupo
$("#grupo_estoque").change(function () {
    //funcionalidade para trazer as informações do subgrupo para os campos automaticamente
    $.ajax({
        type: "POST",
        data: "subgrupo_selecionado=true&id_subgrupo=" + grupo_estoque.value,
        url: "modal/estoque/produto/consultar_grupo.php",
        async: false
    }).then(sucesso, falha);
    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];

        if (($dados.sucesso) ) {//Preencher informações do grupo nos campos dos produtos automaticamente
 
             campo_estoque.value = $dados.estoque_inicial

            campo_estoque_minimo.value = $dados.estoque_minimo

            campo_estoque_maximo.value = $dados.estoque_maximo

            campo_local.value = $dados.local

            campo_unidade_md.value = $dados.unidade

            campo_cfop_externo.value = $dados.cfop_extero
            campo_cfop_interno.value = $dados.cfop_interno

        }

    }
    function falha() {
        console.log("erro grupo selecao")
    }



})