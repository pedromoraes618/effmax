<?php
include "../../../../modal/recibo/gerenciar_recibo.php";
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recibo</title>
    <link href="../../../../css/recibo_quitacao.css" rel="stylesheet">
</head>

<body>
    <div class="recibo-quitacao-container">
        <div class="empresa-info">
            <div class="img">
                <img src="../../../../img/logo.png" width="120" alt="">
            </div>
            <div class="info">
                <p><strong><?php echo $nome_fantasia_empresa; ?></strong></p>
                <p><?php echo $endereco_empresa . " Nº " . $numero_empresa ?></p>
                <p><?php echo $telefone_empresa; ?></p>
                <p>CNPJ: <?php echo $cnpj_empresa; ?></p>
            </div>
        </div>
        <div class="recibo-title">Recibo</div>
        <div class="recibo-info">
            <?= $mensagem; ?>
        </div>
        <div class="assinatura">
            <p>________________________________________</p>
            <small><?= $cidade_empresa . " - " . $estado_empresa; ?></small><br>
            <small><?= formatDateB($data_lancamento); ?></small>

        </div>
    </div>
</body>

</html>