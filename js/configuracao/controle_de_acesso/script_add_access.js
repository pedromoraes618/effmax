

//adicionar acesso

$("#card-body-1 .card_acess").click(function() {
    
  var user_logado = document.getElementById("user_logado").value

    if (usuario_id.value != 0) {
        let id_subcategoria = $(this).attr("id");
        $(this).css("opacity", '1')
        $(this).css("opacity", '0.5')
        $(this).css("display", 'none')
   
        $.ajax({
            type: "POST",
            data: "addicionaracesso=&user_id=" + usuario_id.value + "&idsubcategoria=" +
                id_subcategoria + "&user_logado="+user_logado,
            url: "modal/configuracao/controle_de_acesso/acao_acesso.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {
            $sucesso = $.parseJSON(data)["sucesso"];
        
            if ($sucesso) {
                $.ajax({
                    type: 'GET',
                    data: "refresh&user_id=" + usuario_id.value,
                    url: "view/configuracao/acesso_user/acessos_atuais.php",
                    success: function(result) {
                        return $(".sub_bloco_info-2").html(result);
                    },
                });

            }
        }

        function falha() {
            console.log("erro adicionar acesso ao usuario")
        }

    }
})
