<?php
include "../../../conexao/conexao.php";
include "../../../modal/caixa/abertura_fechamento/gerenciar_caixa.php";


?>
<div class="card status_caixa_card">

    <div class="card-body">
        <h5 class="card-title text-center mb-0"><?php if ($resultado_consulta > 0) {
                                                    if ($status == "aberto" or $status == "reaberto") {
                                                        echo "Caixa Aberto";
                                                    } else {
                                                        echo "Caixa Fechado";
                                                    }
                                                } else {
                                                    echo "Caixa ainda não foi aberto";
                                                } ?></h5>
        <!-- <p class="card-text mb-0"></p> -->
        <!-- <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p> -->
    </div>
    <div class="img text-center">
        <?php if ($resultado_consulta > 0) {
            if ($status == "aberto" or $status=="reaberto") {
                echo "<img class='img-fluid img_status' src='view/caixa/abertura_fechamento/img_status/caixa_aberto.svg' >";
            } else {
                echo "<img class='img-fluid img_status' src='view/caixa/abertura_fechamento/img_status/caixa_fechado.svg' >";
            }
        } else {
            echo "<img class='img-fluid img_status' src='view/caixa/abertura_fechamento/img_status/caixa_nao_aberto.svg' >";
        } ?>
'
    </div>
</div>'