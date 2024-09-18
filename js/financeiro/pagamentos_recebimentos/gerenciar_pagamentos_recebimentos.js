$(document).ready(function() {
 
 $.ajax({
     type: 'GET',
     data: "cunsultar_pagamentos_recebimentos=inicial",
     url: "view/financeiro/pagamentos_recebimentos/consultar_pagamentos_recebimentos.php",
     success: function(result) {
         return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
     },
 });

 
})

