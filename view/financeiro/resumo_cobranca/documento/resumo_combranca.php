<?php
include "../../../../modal/financeiro/resumo_cobranca/gerenciar_resumo_cobranca.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">

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
                    <p class="m-0"><strong><?php echo $nome_fantasia_empresa; ?></strong></p>
                    <!-- <li><?php echo $endereco_empresa; ?></li> -->
                    <p class="m-0">Cnpj: <?php echo formatCNPJCPF($cnpj_empresa); ?></p>

                    <p class="m-0">Tel: <?php echo $telefone_empresa; ?></p>
                    <p class="m-0"><?php echo $email_empresa; ?></p>
                </div>
            </div>
        </div>


        <div class="row ">
            <div class="col text-center ">
                <p class="fw-semibold "><strong> Resumo de cobrança</strong></p>
            </div>
        </div>

        <div class="p-2">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Dados do Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Cliente </strong> <?= $razao_social ?></td>
                        <td><strong>Endereço </strong> <?= $endereco . " - " . $bairro . " - " . $cidade . " - " . $estado; ?></td>

                    </tr>
                    <tr>
                        <td><strong>CNPJ/CPF </strong> <?= $cnpj_cpf; ?></td>
                        <td><strong>Telefone </strong> <?= $telefone; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Email </strong> <?= $email; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-2 mb-3">
            <table>
                <thead>
                    <tr>
                        <th colspan="5">Duplicatas em atraso</th>
                    </tr>
                    <tr>
                        <th scope="col">Data Lançamento</th>
                        <th scope="col">Data vencimento</th>
                        <th scope="col">Atraso</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Valor</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $total = 0;
                    while ($linha = mysqli_fetch_assoc($consultar_resumo_cobranca)) {
                        $data_lancamento = $linha['cl_data_lancamento'];
                        $data_vencimento = $linha['cl_data_vencimento'];
                        $documento = utf8_encode($linha['cl_documento']);
                        $valor = $linha['cl_valor_liquido'];
                        $atraso = $linha['atraso'];
                        $total += $valor;
                    ?>
                        <tr>
                            <td><?= formatDateB($data_lancamento); ?></td>
                            <td><?= formatDateB($data_vencimento); ?></td>
                            <td><?= ($atraso) . " dias"; ?></td>
                            <td><?= $documento; ?></td>
                            <td><?= real_format($valor); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        <th><?= real_format($total) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row mt-4 center-text assinatura">
            <div class="col">
                <hr>
                <p class="mb-4" style="font-size:0.7em"><?= $cidade_empresa . " - " .
                                                            $estado_empresa ?></p>
                <div class="qr-code-container">
                    <img src="data:image/png;base64,<?php echo base64_encode($imageData); ?>" alt="QR Code">
                </div>

            </div>
        </div>

    </div>
    <div class="container ">

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