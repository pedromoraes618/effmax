$(document).ready(function() {
    $.ajax({
        type: 'GET',
        data: "cadastro_categoria=true",
        url: "view/suporte/tela/cadastro_categoria.php",
        success: function(result) {
            return $(".bloco-cadastro-1").html(result);
        },
    });
    //consultar categorias já cadastradas
    $.ajax({
        type: 'GET',
        data: "consultar_tela_categoria=inicial",
        url: "view/suporte/tela/table/consultar_categoria.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });
})

$(".btn_categoria").click(function() {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)
    //verificar se o atributo já possue a clase btn ativo
    if (!($(this).is(".btn_ativo"))) {
        $(this).addClass("btn_ativo");
        $('.btn_subcategoria').removeClass("btn_ativo")
    }

    $.ajax({
        type: 'GET',
        data: "cadastro_categoria=true",
        url: "view/suporte/tela/cadastro_categoria.php",
        success: function(result) {
            return $(".bloco-cadastro-1").html(result);
        },
    });
     //consultar categorias já cadastradas
     $.ajax({
        type: 'GET',
        data: "consultar_tela_categoria=inicial",
        url: "view/suporte/tela/table/consultar_categoria.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });


})


$(".btn_subcategoria").click(function() {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)
    //verificar se o atributo já possue a clase btn ativo
    if (!($(this).is(".btn_ativo"))) {
        $(this).addClass("btn_ativo");
        $('.btn_categoria').removeClass("btn_ativo")
    }

    $.ajax({
        type: 'GET',
        data: "cadastro_categoria=true",
        url: "view/suporte/tela/cadastro_subcategoria.php",
        success: function(result) {
            return $(".bloco-cadastro-1").html(result);
        },
    });
    //consultar subcaategorias já cadastradas
    $.ajax({
        type: 'GET',
        data: "consultar_tela_subcategoria=inicial",
        url: "view/suporte/tela/table/consultar_subcategoria.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });


})

$("#pesquisa_conteudo").click(function() {
    // $('.tabela').css("display", 'none')
    // $('.tabela').fadeIn(500)
    let pesquisa = document.getElementById("conteudo").value;
    //verificar se o atributo já possue a clase btn ativo
    if (($('.btn_categoria').is(".btn_ativo"))) {
        //consultar subcaategorias pelo filtro
        $.ajax({
        type: 'GET',
        data: "consultar_tela_categoria=detalhado&pesquisa="+pesquisa,
        url: "view/suporte/tela/table/consultar_categoria.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });

    }else{
         //consultar subcaategorias pelo filtro
    $.ajax({
        type: 'GET',
        data: "consultar_tela_subcategoria=detalhado&pesquisa="+pesquisa,
        url: "view/suporte/tela/table/consultar_subcategoria.php",
        success: function(result) {
            return $(".tabela").html(result);
        },
    });
    
    }

 

})