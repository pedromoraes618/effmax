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
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnê de Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <link href="../../../../css/carne.css" rel="stylesheet">

</head>

<body>
    <div class="carne">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="details-header d-flex justify-content-between  border-1 border mb-2 ">
                    <div class="d-flex">
                        <div class="img">
                            <img src="../../../../img/logo.png" width="80" alt="">
                        </div>
                        <div class="ms-3">
                            <div><strong><?= $nome_fantasia_empresa; ?></strong></div>
                            <div><?php echo $endereco_empresa; ?></div>
                            <div><?php echo $telefone_empresa; ?></div>
                            <div>CNPJ: <?php echo $cnpj_empresa; ?></div>
                        </div>
                    </div>
                    <div class="d-flex  align-items-center">
                        <div>
                            <h6 class="mb-1">Carnê de pagamento</h6>
                        </div>
                    </div>
                    <div><img src="data:image/png;base64,<?php echo base64_encode($imageData); ?>" alt="QR Code"></div>

                </div>
            </div>
        </div>

        <div class="details row">
            <?php while ($linha = mysqli_fetch_assoc($consultar_lancamentos)) {
                $documento = $linha['cl_documento'];
                $data_lancamento = $linha['cl_data_lancamento'];
                $data_vencimento = $linha['cl_data_vencimento'];
                $valor_bruto = $linha['cl_valor_bruto'];
                $juros = $linha['cl_juros'];
                $desconto = $linha['cl_desconto'];
                $valor_liquido = $linha['cl_valor_liquido'];
                $status_recebimento = $linha['cl_status_id'];
                $data_pagamento = $linha['cl_data_pagamento'];
            ?>
                <div class="item col-12 col-md-6 m-0 p-0">
                    <table class="table table-bordered m-0">
                        <thead>

                            <tr>
                                <th colspan="4"><?= $nome_fantasia_empresa; ?></th>
                            </tr>
                            <tr>
                                <th colspan="4">Cliente: <?= $razao_social_cliente; ?></th>
                            </tr>
                            <tr>
                                <th colspan="4">Endereço: <?= $endereco_cliente; ?></th>
                            </tr>
                            <tr>
                                <th colspan="4">Telefone: <?= $telefone_cliente; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nº <?= $serie_nf; ?> <?= $numero_nf; ?></td>
                                <td>Duplicata: <?= $documento; ?></td>
                                <td>Emissão: <?= formatDateB($data_lancamento); ?></td>
                                <td>Vencimento: <?= formatDateB($data_vencimento); ?></td>
                            </tr>
                            <tr>
                                <td>Valor</td>
                                <td>Juros</td>
                                <td>Desconto</td>
                                <td>Valor Pago</td>
                            </tr>
                            <tr>
                                <td><?= real_format($valor_bruto); ?></td>
                                <td><?php if ($status_recebimento == 2) {
                                        real_format($juros);
                                    } ?></td>
                                <td><?php if ($status_recebimento == 2) {
                                        real_format($desconto);
                                    } ?></td>
                                <td><?php if ($status_recebimento == 2) {
                                        echo real_format($valor_liquido);
                                    }  ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-start">Obs: </td>
                                <td class="<?php if ($status_recebimento != 2) {
                                                echo  'text-start';
                                            } ?> ">Data Pagamento: <?php if ($status_recebimento == 2) {
                                                                        echo  formatDateB($data_pagamento);
                                                                    } ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            <?php } ?>

        </div>

    </div>

    <script src="../../../../js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>