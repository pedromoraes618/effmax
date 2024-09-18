let usuario_id = document.getElementById("select_user");
$("#select_user").change(function() {
    if (usuario_id.value != 0) {

        $.ajax({
            type: 'GET',
            data: "user_id=" + usuario_id.value,
            url: "view/configuracao/acesso_user/acessos_disponiveis.php",
            success: function(result) {
                return $("#card-body-1").html(result);
            },
        });
        $.ajax({
            type: 'GET',
            data: "user_id=" + usuario_id.value,
            url: "view/configuracao/acesso_user/acessos_atuais.php",
            success: function(result) {
                return $(".sub_bloco_info-2").html(result);
            },
        });
    } else {
        $(".sub_bloco_info .card_acess").css("display", "none")
        $(".sub_bloco_info-2 ul").css("display", "none")
    }
})

const acesso_duvida = document.getElementById('duvida');
document.addEventListener('mousedown', (event) => {
    if (acesso_duvida.contains(event.target)) {
        $(".bloco-pesquisa-1 #duvida p").css("display", "block")
        $(".bloco-pesquisa-1 #duvida i").css("display", "none")

    } else {
        $(".bloco-pesquisa-1 #duvida p").css("display", "none")
        $(".bloco-pesquisa-1 #duvida i").css("display", "block")
    }
})