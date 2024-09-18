$(document).ready(function () {
    // 
    var user = document.getElementById('user_logado').value
    periodo(user, "consultar")//verificar qual é o tipo de dashboard do usuário


    $('.periodo').click(function () {
        var acao = $(this).attr('id');
        periodo(user, acao)//atulizar o periodo do dashboard do usuario

    })

    function periodo(usuario, acao) {
        $.ajax({
            type: "POST",
            data: "dashboard_inicial=true&verifica_periodo=" + acao + "&usuario=" + usuario,
            url: "modal/dashboard/inicial/gerenciar_dashboard.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {

            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {

                $('#' + $dados.periodo).attr('checked', 'checked');
                if ($dados.area == "GERENTE") {
                    gerente($dados.periodo,usuario)
                }
                if ($dados.area == "FINANCEIRO") {
                    financeiro($dados.periodo,usuario)
                }
                if ($dados.area == "VENDAS") {
                   
                    vendedor($dados.periodo,usuario)
                }
            }
        }

        function falha() {
            console.log("erro");
        }

    }
})


function gerente(periodo,usuario) {
  
    $.ajax({
        type: 'GET',
        data: "dashboard_inicial=true&status_caixa=true&periodo=" + periodo+"&usuario="+usuario,
        url: "view/dashboard/inicial/bloco/gerente/container_center.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1-dashboard #container-center-1 ").html(result);
        },
    });

    $.ajax({
        type: 'GET',
        data: "dashboard_inicial=truel&status_caixa=true&periodo=" + periodo+"&usuario="+usuario,
        url: "view/dashboard/inicial/bloco/gerente/container_right.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1-dashboard #container-right-1").html(result);
        },
    });

}


function financeiro(periodo,usuario) {

    $.ajax({
        type: 'GET',
        data: "dashboard_inicial=true&status_caixa=true&periodo=" + periodo+"&usuario="+usuario,
        url: "view/dashboard/inicial/bloco/container-center.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1-dashboard #container-center-1 ").html(result);
        },
    });

    $.ajax({
        type: 'GET',
        data: "dashboard_inicial=truel&status_caixa=true&periodo=" + periodo+"&usuario="+usuario,
        url: "view/dashboard/inicial/bloco/container-right-1.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1-dashboard #container-right-1").html(result);
        },
    });

}

function vendedor(periodo,usuario) {
 
    $.ajax({
        type: 'GET',
        data: "dashboard_inicial=true&status_caixa=true&periodo=" + periodo+"&usuario="+usuario,
        url: "view/dashboard/inicial/bloco/venda/container_center.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1-dashboard #container-center-1 ").html(result);
        },
    });

    $.ajax({
        type: 'GET',
        data: "dashboard_inicial=truel&status_caixa=true&periodo=" + periodo+"&usuario="+usuario,
        url: "view/dashboard/inicial/bloco/venda/container_right.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1-dashboard #container-right-1").html(result);
        },
    });

}
