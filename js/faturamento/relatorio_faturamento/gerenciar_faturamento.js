$(document).ready(function() {
 
 $.ajax({
     type: 'GET',
     data: "cunsultar_relatorio_faturamento=inicial",
     url: "view/faturamento/relatorio_faturamento/consultar_faturamento.php",
     success: function(result) {
         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
     },
 });

 
})

