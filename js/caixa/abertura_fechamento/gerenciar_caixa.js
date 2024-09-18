$(document).ready(function() {
 
 $.ajax({
     type: 'GET',
     data: "cunsulta_caixa=inicial",
     url: "view/caixa/abertura_fechamento/consultar_caixa.php",
     success: function(result) {
         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
     },
 });

 
})