$(document).ready(function() {
var id_produto = document.getElementById("id")

$.ajax({
    type: 'GET',
    data: "kardex_produto=inicial&id_produto="+id_produto.value,
    url: "view/estoque/kardex/table/consultar_kardex.php",
    success: function(result) {
        return $("#modal_kardex .tabela").html(result)
    },
});

})

$("#pesquisar_filtro_kardex").click(function(){
    var id_produto = document.getElementById("id")
    data_inicial = $("#data_inicial").val()
    data_final = $("#data_final").val()
    conteudo_pesquisa_kardex = $("#pesquisa_conteudo_kardex").val()
    
    $.ajax({
        type: 'GET',
        data: "kardex_produto=detalhado&id_produto="+id_produto.value+"&data_inicial="+data_inicial+"&data_final="+data_final+"&conteudo_pesquisa_kardex="+conteudo_pesquisa_kardex,
        url: "view/estoque/kardex/table/consultar_kardex.php",
        success: function(result) {
            return $("#modal_kardex .tabela").html(result);
        },
    });
})

// /*se o usuario vier da tela de gerenciar produto */
// $("#voltar_consulta").click(function(e) {
  
//     $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block") // remover tela de cadastro
//     $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de consulta
//         $.ajax({
//         type: 'GET',
//         data: "consultar_produto=",
//         url: "view/estoque/produto/consultar_produto.php",
//         success: function(result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
//         },
//     });
    
// })
    

// /*se o usuario vier da tela de consultar produto */
// $("#voltar_visualizar_consulta").click(function(e) {
//     $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block") // remover tela de cadastro
//     $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de consulta
//         $.ajax({
//         type: 'GET',
//         data: "consultar_produto=",
//         url: "view/estoque/consultar_produto/consultar_produto.php",
//         success: function(result) {
//         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
//         },
//     });
    
// })