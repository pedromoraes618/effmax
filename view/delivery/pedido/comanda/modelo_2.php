<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
include "../../../../modal/delivery/pedido/gerenciar_pedido.php";
include '../../../../biblioteca/phpqrcode/qrlib.php';

$tamanho = 2;
$margem = 0;

ob_start();
QRcode::png($url_qrdcode, null, QR_ECLEVEL_L, $tamanho, $margem);
$imageData = ob_get_contents();
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="pt-Br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../../../css/comanda.css" rel="stylesheet">
    <title>Comanda</title>
</head>

<body>
    <div class="comanda">


        <div class="comanda-details">
            <div>
                <img width="80" src="../../../../img/logo.png" alt="">
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
        <div class="comanda-details-cliente">
            <p><strong>Nº Pedido: <?php echo ($numero_pedido); ?></strong></p>
            <p>Data Pedido: <?php echo formatarTimeStamp($data_pedido_delivery); ?></p>
            <p>Entrega Estimada: <?php echo calcularHoraEntrega($data_pedido_delivery, $tempo_entrega_pedido); ?></p>
            <p>Pagamento: </strong><?php echo $formapgt; ?></p>
            <p><strong> Opção: <?php echo formatarTexto($metodo_entrega); ?></strong></p>
        </div>

        <div class="divider">
            <hr>
        </div>
        <strong>
            <?php if ($usuario_id == "") {
            ?>
                <div class="comanda-details-cliente">
                    <p>Cliente: <?php echo $cliente_avulso; ?></p>
                    <?php if ($metodo_entrega == "ENTREGA") { ?>
                        <p>Endereço:
        </strong><?php echo $endereco_entrega_delivery; ?></p>
    <?php } ?>

    </div>
<?php
            } else {
?>
    <div class="comanda-details-cliente">
        <p>Cliente: <?php echo $usuario_delivery; ?></p>
        <p>Telefone:</strong> <?php echo $telefone; ?></p>
        <?php if ($metodo_entrega == "ENTREGA") { ?>
            <p>Endereço: </strong><?php echo $bairro_entrega_delivery . ", " . $endereco_entrega_delivery . ", " . $numero_casa_delivery; ?></p>
        <?php } ?>

    </div>
<?php
            } ?>
</strong>

<div class="divider">
    <hr>
</div>

<div class="product-list">
    <h3>Itens</h3>
    <ul>
        <?php

        /*tela de pedidos do usuario */
        $select = "SELECT * from tb_nf_saida_item where  cl_tipo_item_delivery ='PRODUTO' and cl_codigo_nf ='$codigo_nf'  ";
        $consulta_produto = mysqli_query($conecta, $select);
        $qtd_consulta_produto = mysqli_num_rows($consulta_produto);

        $valor_total = 0;
        $valor_item_total = 0;
        while ($linha = mysqli_fetch_assoc($consulta_produto)) {
            $id_delivery = ($linha['cl_id_delivery']);
            $titulo = utf8_encode($linha['cl_descricao_item']);
            $valor_unitario = ($linha['cl_valor_unitario']);
            $quantidade_produto = ($linha['cl_quantidade']);
            $observacao_produto = utf8_encode($linha['cl_observacao_delivery']);
            $valor_produto = $valor_unitario * $quantidade_produto;

        ?>
            <li class="product-item">
                <div class="group-text-title-produto">
                    <span style="font-size: 1.15em;"> <strong><?php echo $titulo; ?></strong></span>
                    <span><span style="margin-right: 5px;">Qtd: <?php echo $quantidade_produto; ?></span> Valor: <?php echo real_format($valor_produto); ?></span>
                </div>
                <ul class="subproduct-list">
                    <?php
                    $select = "SELECT cl_descricao_item,cl_valor_unitario,sub.cl_descricao as grupo,cl_quantidade,cl_valor_total,cl_tipo_adicional_delivery,
                                  cl_tipo_item_delivery from tb_nf_saida_item as nfi inner join tb_produtos as prd on 
                                  prd.cl_id = nfi.cl_item_id inner join tb_subgrupo_estoque as sub on sub.cl_id  = prd.cl_grupo_id   where  
                              (cl_tipo_item_delivery ='ADICIONAL' or cl_tipo_item_delivery ='COMPLEMENTO') and cl_codigo_nf ='$codigo_nf' 
                              and (cl_id_pai_delivery ='$id_delivery' )  order by prd.cl_grupo_id   desc ";
                    $consulta_itens = mysqli_query($conecta, $select);
                    //   $qtd_consulta_produto = mysqli_num_rows($consulta_produto);
                    while ($linha = mysqli_fetch_assoc($consulta_itens)) {
                        $titulo = utf8_encode($linha['cl_descricao_item']);
                        $quantidade = ($linha['cl_quantidade']);
                        $valor_unitario = ($linha['cl_valor_unitario']);

                        $tipo_adicional_delivery = ($linha['cl_tipo_adicional_delivery']);
                        $tipo_item_delivery = ($linha['cl_tipo_item_delivery']);
                        $grupo = utf8_encode($linha['grupo']);

                        $valor = $quantidade * $valor_unitario; //valor total do produto

                        $tipo_item_delivery_span = '<span class="badge rounded-pill text-bg-primary">' . formatarTexto($tipo_item_delivery) . '</span>';
                        if ($tipo_adicional_delivery = "PAGO") {
                            $tipo_adicional_delivery_span = '+';
                            // $valor = 0;
                        } else {
                            $tipo_adicional_delivery = "";
                        }


                    ?>
                        <li class="subproduct-item">
                            <span><?php echo $tipo_adicional_delivery_span . " " .  $titulo . " - "  . $grupo; ?> </span>
                            <span>Qtd: <?php echo $quantidade; ?> Valor: <?php echo real_format($valor); ?></span>
                        </li>

                    <?php } ?>
                    <li><?php if ($observacao_produto != "") {
                            echo "Obs: " . $observacao_produto;
                        } ?></li>
                    <!-- Adicione mais subprodutos conforme necessário -->
                </ul>
            </li>
        <?php } ?>

    </ul>
</div>

<div class="divider">
    <hr>
</div>

<div class="total">
    <p><strong>Sub Total:</strong> <?php echo real_format($valor_bruto); ?></p>

    <?php if ($metodo_entrega == "ENTREGA") { ?>
        <p><strong>Taxa de Entrega:</strong> <?php if ($valor_entrega_delivery == 0) {
                                                    echo 'Grátis';
                                                } else {
                                                    echo $valor_entrega_delivery;
                                                } ?></p>
    <?php } ?>
    <?php if ($desconto > 0) { ?>
        <p><strong>Desconto: </strong> <?php echo real_format($desconto); ?></p>
    <?php } ?>
    <p><strong>Total a Pagar:</strong> <?php echo real_format($valor_liquido); ?></p>
</div>

<div class="divider">
    <hr>
</div>
<div class="footer"><?php if ($observacao != "") {
                        echo "Observação <br> $observacao";
                    } ?> </div>
<div style="text-align:center;margin-bottom:15px">Documento não fiscal</div>
<div class="qr-code-container" style="text-align: center;">
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