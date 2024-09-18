



$("#buscar_cest").click(function () {
    var consulta = document.getElementById("conteudo_pesquisa").value

    $.ajax({
        type: 'GET',
        data: "consultar_cest=true&conteudo_pesquisa=" + consulta,
        url: "view/estoque/produto/table/consultar_cest_ncm.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu #modal_cunsultar_cest_ncm .tabela").html(result);
        },
    });

})

//pesquisar ncm e informar no campo
$("#buscar_ncm").click(function () {

    var consulta = document.getElementById("conteudo_pesquisa").value
    $.ajax({
        type: 'GET',
        data: "consultar_ncm=true&conteudo_pesquisa=" + consulta,
        url: "view/estoque/produto/table/consultar_cest_ncm.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu #modal_cunsultar_cest_ncm .tabela").html(result);
        },
    });



})


$(".selecionar_cest").click(function () {
  
    var cest = $(this).attr("valor")
    var input_cest = document.getElementById("cest")
    input_cest.value = cest
    $("#modal_cunsultar_cest_ncm").modal('hide');//fechar o modal

})



$(".selecionar_ncm").click(function () {
    var ncm = $(this).attr("valor")
    var input_ncm = document.getElementById("ncm")
    input_ncm.value = ncm
    $('#modal_cunsultar_cest_ncm').modal('hide')//fechar o modal
})

