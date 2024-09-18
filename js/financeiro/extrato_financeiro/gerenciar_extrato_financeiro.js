$(document).ready(function() {

 $.ajax({
     type: 'GET',
     data: "cunsultar_extrato_financeiro=inicial",
     url: "view/financeiro/extrato_financeiro/consultar_extrato_financeiro.php",
     success: function(result) {
         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
     },
     
 });

})

