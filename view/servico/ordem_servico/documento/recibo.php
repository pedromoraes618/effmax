<?php include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordem serviço</title>
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
            <div class="border   border-light-subtle p-2">
                <p class="mb-1">Doc: <strong> <?= $serie_nf . $numero_nf; ?></strong></p>
                <p class="mb-1">Tipo Serviço: <strong><?php echo $tiposervico; ?></strong></p>
                <P class="mb-1">Abertura: <strong><?php echo formatDateB($data_abertura); ?></strong></P>
                <P class="mb-1">Atedente: <strong><?= $atendente; ?></strong></P>

            </div>
        </div>


        <div class="row ">
            <div class="col text-center ">
                <p class="fw-semibold "><strong> Recibo de Ordem de Serviço</strong></p>
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
                        <td><strong>Endereço </strong> <?= " $endereco - $bairro - $cidade "; ?></td>

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
                        <th colspan="2">Equipamento</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Descrição </strong> <?= $equipamento; ?></td>
                        <td><strong>Nº Serie </strong> <?= $numero_serie; ?></td>

                    </tr>

                    <tr>
                        <td><strong>Defeito Informado </strong> <?= $defeito_informado; ?></td>
                        <td><strong>Diagnóstico </strong> <?= $defeito_constatado; ?></td>
                    </tr>

                    <tr>
                        <td colspan="2"><strong>Observação </strong> <?= $observacao; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="obs p-2">
            <p><?= $condicao_servico; ?></p>
        </div>
        <div class="row center-text assinatura">
            <div class="col">
                <hr>
                <p class="mb-0">Assinatura do Cliente</p>
                <p class="mb-4" style="font-size:0.7em"><?= $cidade_empresa . " - " .
                                                            $estado_empresa ?></p>
                <div class="qr-code-container">
                    <img src="data:image/png;base64,<?php echo base64_encode($imageData); ?>" alt="QR Code">
                </div>

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