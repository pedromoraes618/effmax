<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/venda_mercadoria_delivery/gerenciar_venda.php";
include "../../../../funcao/funcao.php";
include '../../../../biblioteca/phpqrcode/qrlib.php';


// Tamanho e margem do QR Code
$tamanho = 3;
$margem = 2;

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
    <div class="container">
        <div class="header">
            <h1 class="title">Recibo</h1>
        </div>
        <div class="info">
            <table>
                <tr>
                    <th>
                        <p>Data de Emissão: <?php echo formatDateB($data_movimento_b); ?> Nº Venda: <?php echo $numero_nf_b; ?></p>
                    </th>
                </tr>

                <tr>
                    <th>
                        <p>Empresa: <?php echo $nome_fantasia_empresa; ?></p>
                    </th>

                </tr>
                <tr>
                    <th>
                        <p>CNPJ: <?php echo formatCNPJCPF($cnpj_empresa); ?></p>
                    </th>

                </tr>
                <tr>
                    <th>
                        <p>Endereço: <?php echo $endereco_empresa . " " . $numero_empresa ?></p>
                    </th>
                </tr>
                <tr>
                    <th>
                        <p>Cep: <?php echo $cep_empresa; ?> Telefone: <?php echo $telefone_empresa ?></p>
                    </th>
                </tr>
                <tr>
                    <th>
                        <p><?php echo $cidade_empresa . " " . $estado_empresa ?></p>
                    </th>
                </tr>
            </table>
        </div>

        <div class="products">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Un</th>
                        <th>Vlr unit</th>
                        <th>Vlr total</th>
                    </tr>
                </thead>
                <tbody>
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
                        <tr>
                            <td><?php echo $item; ?></td>
                            <td><?php echo $descricao; ?></td>
                            <td><?php echo $quantidade; ?></td>
                            <td><?php echo $unidade; ?></td>
                            <td><?php echo real_format($valor_unitario); ?></td>
                            <td><?php echo real_format($valor_total); ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="5">Total</td>
                        <td><?php echo real_format($vlr_total_itens); ?></td>
                    </tr>

                    <?php if ($valor_desconto_b > 0) { ?>
                        <tr>
                            <td colspan="5">Desconto</td>
                            <td><?php echo real_format($valor_desconto_b); ?></td>
                        </tr>
                        <tr>
                            <td colspan="5">Total</td>
                            <td><?php echo real_format($vlr_total_itens - $valor_desconto_b); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="footer">
            <p>Forma de Pagamento: <?php echo $forma_pagamento_b; ?></p>
            <p>Vendedor: <?php echo $vendedor_b; ?></p>
            <p>Doc sem valor fiscal</p>
            <p>Obrigado pela preferência!</p>
        </div>
        <?php if ($observacao != "") { ?>
            <div class="observation">
                <p>Observação:<br><?php echo $observacao; ?></p>
            </div>
        <?php } ?>

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