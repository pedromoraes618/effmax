$(document).ready(function () {


    resumo_cobranca()



    $("#resumo_cobranca").click(function () {
        $(this).addClass('active').siblings().removeClass('active');

        resumo_cobranca()
    })
    $("#resumo_periodo").click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        resumo_periodo()
    })

    function resumo_cobranca() {
        $.ajax({
            type: 'GET',
            data: "cunsultar_resumo_cobranca=inicial",
            url: "view/financeiro/resumo_cobranca/consultar_resumo_cobranca.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
            },

        });
    }

    function resumo_periodo() {
        $.ajax({
            type: 'GET',
            data: "cunsultar_resumo_preiodo=inicial",
            url: "view/financeiro/resumo_cobranca/consultar_resumo_periodo.php",
            success: function (result) {
                return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
            },

        });
    }

})

