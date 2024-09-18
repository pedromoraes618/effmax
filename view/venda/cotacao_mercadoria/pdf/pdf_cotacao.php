<?php include "../../../../modal/venda/cotacao_mercadoria/gerenciar_cotacao.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotação</title>
    <!-- Adicione o link para o Bootstrap CSS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../../../../css/pdf_2.css" rel="stylesheet">

</head>

<body>
    <div class="container">

        <div class="header d-flex justify-content-between">
            <div class="d-flex">
                <div class="logo">
                    <img src="../../../../img/logo.png" width="90">
                </div>
                <div class="mx-2 ">
                    <p class="m-0"><strong><?= $nome_fantasia_empresa; ?></strong></p>
                    <!-- <li><?= $endereco_empresa; ?></li> -->
                    <p class="m-0">Cnpj: <?= formatCNPJCPF($cnpj_empresa); ?></p>

                    <p class="m-0">Tel: <?= $telefone_empresa; ?></p>
                    <p class="m-0"><?= $email_empresa; ?></p>
                </div>
            </div>
            <div class="border   border-light-subtle p-2">
                <P class="mb-1"> <strong>Data Emissão: </strong><?= formatDateB($data_movimento); ?></P>
                <p class="mb-1"> <strong>Cotação Nº: </strong> <?= $numero_nf; ?></p>
                <p class="mb-1"><strong>Validade: </strong><?= $validade; ?></p>
            </div>
        </div>
        <div class="row ">
            <div class="col text-center ">
                <p class="fw-semibold "><strong> Registro de Cotação</strong></p>
            </div>
        </div>
        <div class="p-2">
            <table>
                <thead>
                    <tr>
                        <th>Dados do cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Cliente: </strong> <?= $cliente ?></td>
                    </tr>
                    <tr>
                        <td><strong>Observação: </strong> <?= $observacao; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-2">
            <table>
                <thead>
                    <tr>
                        <th colspan="9">Itens</th>
                    </tr>
                    <tr>
                        <th>Item</th>
                        <th>Descrição</th>
                        <th>Ref</th>
                        <th>Ncm</th>
                        <th>Und</th>
                        <th>Vlr Unit</th>
                        <th>Qtd</th>
                        <th>Total</th>
                        <th>Prazo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $somatorio_total = 0;
                    $item = 0;
                    while ($linha = mysqli_fetch_assoc($consultar_produtos)) {
                        $item = $item + 1;

                        $id = $linha['cl_id'];
                        $item_id = $linha['cl_item_id'];
                        $descricao = utf8_encode($linha['cl_descricao_item']);
                        $unidade = utf8_encode($linha['cl_unidade']);
                        $referencia = utf8_encode($linha['cl_referencia']);
                        $quantidade = $linha['cl_quantidade'];
                        $valor_unitario = $linha['cl_valor_unitario'];
                        $ncm = $linha['cl_ncm'];
                        //   $desconto_item = $linha['cl_desconto_item'];
                        $Valor_total = $linha['cl_valor_total'];
                        $prazo_entrega = $linha['cl_prazo_entrega'];
                        if ($prazo_entrega > 0) {
                            $prazo_entrega = $prazo_entrega . " dias ";
                        } else {
                            $prazo_entrega = "Imediato";
                        }

                        $somatorio_total = $Valor_total + $somatorio_total;

                    ?>
                        <tr>
                            <td style="width: 70px;"><?= $item; ?></td>
                            <td><?= ($descricao); ?></td>
                            <td><?= $referencia; ?></td>
                            <td><?= $ncm; ?></td>
                            <td><?= $unidade; ?></td>
                            <td><?= real_format($valor_unitario); ?></td>
                            <td><?= ($quantidade); ?></td>
                            <td style="width:150px;"><?= real_format($Valor_total); ?></td>
                            <td><?= ($prazo_entrega); ?></td>
                        </tr>
                    <?php } ?>

                    <!-- Adicione mais produtos aqui, se necessário -->
                    <tr>
                        <td colspan="7"><strong><?= $valor_desconto > 0 ? 'Subtotal' : 'Total'; ?></strong></td>
                        <td><?= $valor_desconto > 0 ? real_format($valor_bruto) : real_format($valor_liquido);
                            ?>
                        </td>
                        <td></td>
                    </tr>
                    <?php if ($valor_desconto > 0) { ?>
                        <tr>
                            <td colspan="7"><strong>Desconto</strong></td>
                            <td><?= real_format($valor_desconto); ?></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="7"><strong>Total</strong></td>
                            <td><?= real_format($valor_liquido); ?></td>
                            <td></td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
        <div class="obs p-2">
            <p><?= $condicao_cotacao; ?></p>
        </div>
        <div class="row center-text assinatura">
            <div class="col">
                <hr>
                <p class="mb-0">Assinatura do responsável </p>
                <p class="mb-4" style="font-size:0.7em"><?= $cidade_empresa . " - " .
                                                            $estado_empresa ?></p>
                <p>
                <div class="qr-code-container">
                    <img src="data:image/png;base64,<?php echo base64_encode($imageData); ?>" alt="QR Code">
                </div>
                </p>
            </div>

        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

    <!-- Adicione o link para o Bootstrap JS e jQuery (opcional) no final do documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>