
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo")

//condição se existe valor de pesquisa no localstorage recarregar a pesquisa automaticamente
//valor da pesquisa é guardado no localStorage, ao clicar em editar ou adicionar a pagina realizara a pesquisa novamente
if (localStorage.getItem("storage_pesquisa")) {
    var memoria_pesquisa = localStorage.getItem("storage_pesquisa");
    conteudo_pesquisa.value = memoria_pesquisa
    $.ajax({
        type: 'GET',
        data: "consultar_produto=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/estoque/consultar_produto/table/consultar_produto.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
}else{

//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_produto=inicial",
    url: "view/estoque/consultar_produto/table/consultar_produto.php",
    success: function(result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});
}



$("#pesquisar_filtro_pesquisa").click(function(e) {
 
    localStorage.setItem("storage_pesquisa", conteudo_pesquisa.value);
    if(conteudo_pesquisa.value==""){
        $(".alerta").html("<span class='alert alert-primary position-absolute' style role='alert'>Favor informe a palavra chave</span>")
       setTimeout(function() {
        $(".alerta .alert").css("display","none")
      }, 5000);
    }else{
    $.ajax({
        type: 'GET',
        data: "consultar_produto=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/estoque/consultar_produto/table/consultar_produto.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
}
})


