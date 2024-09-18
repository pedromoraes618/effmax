$(document).ready(function() {
 
 $.ajax({
     type: 'GET',
     data: "cunsulta_movimento_caixa=inicial",
     url: "view/caixa/movimento_caixa/consultar_movimento_caixa.php",
     success: function(result) {
         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
     },
 });

 
})