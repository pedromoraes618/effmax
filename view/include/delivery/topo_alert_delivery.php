<?php

include "../../../modal/delivery/pedido/gerenciar_pedido.php";

if ($qtd_consulta_pedido_confirmacao_topo > 0) { ?>
    <div class="alert-topo-delivery p-1 rounded ">
        <a href="?menu&ctg=Delivery&pedidodl&id=30">
            <i class="bi bi-lightbulb-fill"></i> Pedido no Delivery
        </a>
    </div>

    <script>
        playSound("audio/alert.mp3");
    </script>
<?php } elseif ($qtd_consulta_pd_solicitacao_cancelar_topo > 0) {
?>
    <div class="alert-topo-delivery p-1 rounded ">
        <a href="?menu&ctg=Delivery&pedidodl&id=30">
            <i class="bi bi-lightbulb-fill "></i> Cancelamento de Pedido
        </a>
    </div>

    <script>
        playSound("audio/alert.mp3");
    </script>
<?php
} ?>