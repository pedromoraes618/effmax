<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
include "../../../../modal/venda/venda_mercadoria/gerenciar_venda.php";

include '../../../../biblioteca/phpqrcode/qrlib.php';


// Tamanho e margem do QR Code
$tamanho = 2;
$margem = 0;

// Renderize o QR Code em um buffer de saída
ob_start();
QRcode::png($url_qrdcode, null, QR_ECLEVEL_L, $tamanho, $margem);
$imageData = ob_get_contents();
ob_end_clean();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Recibo</title>
    <link href="../../../../css/recibo.css" rel="stylesheet">
</head>

<body>
    <div class="recibo">
        <div class="recibo-details-header">
            <div class="img">
                <img src="../../../../img/logo.png" width="80" alt="">
            </div>

            <div>
                <p><strong><?php echo $nome_fantasia_empresa; ?></strong></p>
                <p><?php echo $endereco_empresa; ?></p>
                <p><?php echo $telefone_empresa; ?></p>
                <p>CNPJ: <?php echo $cnpj_empresa; ?></p>
            </div>
        </div>


        <div class="divider">
            <hr>
        </div>
        <div class="recibo-details-info">
            <p><strong>Nº: <?php echo ($numero_nf_b); ?></strong></p>
            <p>Data: <?php echo formatarTimeStamp($data_venda); ?></p>
            <p>Pagamento: </strong><?php echo $forma_pagamento_b; ?></p>
            <p>Cliente: </strong><?php echo $razao_social_b; ?></p>
        </div>

        <div class="divider">
            <hr>
        </div>
        <div class="product-list">
            <h3>Itens</h3>
            <ul>

                <?php
                $item = 0;
                $vlr_total_itens = 0;
                while ($linha = mysqli_fetch_assoc($consultar_nf_saida_item)) {
                    $item = $item + 1;
                    $item_id = ($linha['cl_item_id']);
                    $descricao = utf8_encode($linha['cl_descricao_item']);
                    $quantidade = $linha['cl_quantidade'];
                    $unidade = utf8_encode($linha['cl_unidade']);
                    $valor_unitario = $linha['cl_valor_unitario'];
                    $valor_total = $linha['cl_valor_total'];
                    $vlr_total_itens = $valor_total + $vlr_total_itens;
                ?>
                    <li class="product-item">
                        <div class="group-text-title-produto">
                            <span style="font-size: 1.15em;"> <strong><?php echo $descricao; ?></strong></span>
                            <span><span style="margin-right: 5px;">Qtd: <?php echo $quantidade; ?></span> Valor: <?php echo real_format($valor_total); ?></span>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="divider">
            <hr>
        </div>

        <div class="total">
            <p><strong>Sub Total:</strong> <?php echo real_format($valor_bruto_b); ?></p>
            <?php if ($valor_desconto_b > 0) { ?>
                <p><strong>Desconto: </strong> <?php echo real_format($valor_desconto_b); ?></p>
            <?php } ?>
            <p><strong>Total a Pagar:</strong> <?php echo real_format($valor_liquido_b); ?></p>
        </div>

        <div class="divider">
            <hr>
        </div>
        <div class="footer"><?php if ($observacao != "") {
                                echo "Observação <br> $observacao";
                            } ?> </div>
        <div style="text-align:center;margin-bottom:10px">Documento não Fiscal</div>
        <div class="qr-code-container">
            <img src="data:image/png;base64,<?php echo base64_encode($imageData); ?>" alt="QR Code">
        </div>

    </div>
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>

</html>