<div class="topo">

    <?php include "view/nav/menu_mobile.php" ?>

    <div class="left">
        <p><?php echo $categoria_top; ?></p>
    </div>
    <div class="right" aria-expanded="false">
        <div class="alert-topo-delivery-pai ">

        </div>
        <button class="btn btn-outline-secondary border-0 text-light" id="fullscreenBtn">
            <i class="bi bi-fullscreen"></i></button>


        <button class="btn btn-outline-secondary border-0 text-light" id="abrir_chat" title="Chat, pergunte a sua dúvida" data-bs-toggle="modal" data-bs-target="#modal_cunsultar_chat" data-bs-whatever="@mdo" type="img">
            <i class="bi bi-chat-left-text-fill"></i></i></button>


        <nav id="dropdown_notificacao">
            <ul class="btn btn-outline-secondary border-0 text-light">

                <li> <i class="bi bi-bell-fill"></i>
                    <?php if (($qtd_lembrete + $qtd_atendimento) > 0) {
                    ?>
                        <span class="position-absolute top-0 start-10 translate-middle badge rounded-pill bg-success">
                            <?php echo ($qtd_lembrete + $qtd_atendimento); ?>
                        </span>
                    <?php
                    } ?>
                    <ul class="menu_user">
                        <div class="title"><?php echo $qtd_lembrete + $qtd_atendimento; ?> Notificação</div>
                        <hr class="mb-0 mt-1">
                        <li>
                            <a href="?menu&ctg=Empresa&atd&id=40"><i style="font-size:0.9em;" class="bi bi-person-lines-fill"></i> <?php echo $qtd_atendimento; ?>
                                Atendimento</a>
                        </li>
                        <li>
                            <a href="?menu&ctg=Lembrete&mtask&id=10"><i style="font-size:0.9em;" class="bi bi-list-task"></i> <?php echo $qtd_lembrete; ?> Tarefa</a>
                        </li>


                    </ul>
                </li>
            </ul>

        </nav>

        <!-- <button type="img" class="btn btn-outline-secondary border-0 text-light btn_notificacao">
            <a href="?menu&ctg=Lembrete&mtask&id=10">
                <i class="bi bi-bell-fill"></i>
            </a>

            <?php if ($qtd_lembrete > 0) {
            ?>
                <span class="position-absolute top-0 start-10 translate-middle badge rounded-pill bg-success">
                    <?php echo $qtd_lembrete; ?>
                <?php
            } ?>
                </span>
        </button> -->
        <nav id="dropdown_user">
            <ul class="btn btn-outline-secondary  border-0 text-light">
                <li><?php echo ($img != '') ? "<img class='img_user_topo' src='img/usuario/$img' >" : "<i class='bi bi-person  text-light '></i>" ?>
                    <ul class="menu_user">
                        <li>
                            <a href="?menu&ctg=Configuração&myuser&id=22"><i style="font-size:0.9em;" class="bi bi-person-circle"></i>
                                Meu Usuário</a>
                        </li>
                        <li>
                            <a href="?menu&ctg=Configuração&sobre&id=14"><i style="font-size:0.9em;" class="bi bi-file-text-fill"></i> Sobre</a>
                        </li>
                        <li>
                            <a href="?logout"><i style="font-size:0.9em;" class="bi bi-box-arrow-left"></i> Sair</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="user">
                <p><?php echo $usuario; ?></p>
            </div>
        </nav>
    </div>

</div>
<?php
// include "ajuda/chat.php";
if (isset($_GET['ctg']) and isset($_GET['id'])) {
?>
    <div class="bloco-topo">
        <p> <?php echo $sub_top; ?></p>
    </div>

<?php
}
?>
<script src="js/jquery.js"></script>
<script src="js/tutorial.js"></script>
<script>
    const acesso_menu_user = document.getElementById('dropdown_user');
    document.addEventListener('mousedown', (event) => {
        if (acesso_menu_user.contains(event.target)) {
            $(".right ul li ul").css("display", "block")

        } else {
            $(".right ul li ul").css("display", "none")
        }
    })

    const acesso_menu_noticacao = document.getElementById('dropdown_notificacao');
    document.addEventListener('mousedown', (event) => {
        if (acesso_menu_noticacao.contains(event.target)) {
            $(".right #dropdown_notificacao ul li ul").css("display", "block")

        } else {
            $(".right #dropdown_notificacao ul li ul").css("display", "none")
        }
    })


    function playSound(audioName) {
        let audio = new Audio(audioName)
        audio.loop = false
        audio.play();
    }

    atualizar_pedido_topo()

    function atualizar_pedido_topo() {
        $.ajax({
            type: 'GET',
            data: "consultar_pedido=inicial",
            url: "view/include/delivery/topo_alert_delivery.php",
            success: function(result) {
                return $(".alert-topo-delivery-pai ").html(result);
            },
        });
    }


    setInterval(atualizar_pedido_topo, 60000); // 60 segundos em milissegundos





    /*fullscreen*/
    document.addEventListener("DOMContentLoaded", function() {
        var loaderWrapper = document.querySelector(".loader-wrapper");
        loaderWrapper.style.display = "none";
    });

    $(document).ready(function() {
        $('#fullscreenBtn').on('click', function() {
            let elem = document.documentElement;

            // Verifica se está em tela cheia
            if (!document.fullscreenElement && // Padrão
                !document.mozFullScreenElement && // Firefox
                !document.webkitFullscreenElement && // Chrome, Safari e Opera
                !document.msFullscreenElement) { // IE/Edge

                // Entrar em tela cheia
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { // Firefox
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { // Chrome, Safari e Opera
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { // IE/Edge
                    elem.msRequestFullscreen();
                }
            } else {
                // Sair do modo de tela cheia
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) { // Firefox
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) { // Chrome, Safari e Opera
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { // IE/Edge
                    document.msExitFullscreen();
                }
            }
        });
    });
</script>