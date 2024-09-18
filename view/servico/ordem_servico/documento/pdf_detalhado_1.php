<?php include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordem de serviço</title>
    <!-- Adicione o link para o Bootstrap CSS -->


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../../../../css/pdf_2.css" rel="stylesheet">
    <!-- icons bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
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
                <P class="mb-1">Abertura: <strong><?php echo formatDateB($data_abertura); ?></strong></P>
                <P class="mb-1">Atedente: <strong><?= $atendente; ?></strong></P>
            </div>
        </div>
        <div class="row ">
            <div class="col text-center ">
                <p class="fw-semibold "><strong> Ordem de Serviço</strong></p>
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
                        <td><strong>Endereço </strong> <?= "$endereco - $bairro - $cidade"; ?></td>
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

        <div class="p-2">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Detalhe do Serviço</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Tipo Serviço: </strong> <?= $tiposervico; ?></td>
                        <td><strong>Status: </strong> <?= $status_ordem; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Descrição: </strong> <?= $descricao_atividade; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Local: </strong> <?= $local_atividade; ?></td>
                    </tr>

                    <tr>
                        <td colspan="2"><strong>Observação: </strong> <?= $observacao; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-2">
            <table>
                <?php
                $total = 0;
                $item = 0;
                while ($linha = mysqli_fetch_assoc($consulta_servicos)) {
                    $item += 1;
                    $id = utf8_encode($linha['cl_id']);
                    $descricao = utf8_encode($linha['cl_item_descricao']);
                    $referencia = $linha['cl_referencia'];
                    $qtd_orcada = $linha['cl_quantidade_orcada'];
                    $qtd_requisitada = $linha['cl_quantidade_requisitada'];
                    $unidade = utf8_encode($linha['cl_unidade']);
                    $valor_total = $linha['cl_valor_total'];
                    $valor_unitario = $linha['cl_valor_unitario'];
                    $responsavel = utf8_encode($linha['cl_nome']);
                    $data_inicio = formatDateB($linha['cl_data_inicio']);
                    $data_fim = formatDateB($linha['cl_data_fim']);
                    $total += $valor_total;
                ?>
                    <thead>
                        <tr>
                            <th colspan="5"><i class="bi bi-tools"></i> Serviço</th>
                        </tr>
                        <tr>
                            <th style="width: 50px;">Item</th>
                            <th>Líder</th>
                            <th>Serviço</th>
                            <th>Data Inicio</th>
                            <th>Data Final</th>
                        </tr>
                    </thead>

                    <tr>
                        <td><?= $item ?></td>
                        <td><?= $responsavel ?></td>
                        <td><?= $descricao; ?></td>
                        <td><?= $data_inicio; ?></td>
                        <td><?= $data_fim; ?></td>
                    </tr>

                    <!-- Listagem de Produtos de Uso e Consumo e Bens Ativos -->
                    <tr>
                        <td colspan="5">
                            <table class="mb-2">
                                <thead>
                                    <tr>
                                        <th colspan="2">Material - Ativo Imobilizado</th>
                                    </tr>
                                    <tr>
                                        <td>Descrição</td>
                                        <td>Quantidade</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_infraestrutura_os WHERE cl_servico_destinado_id = '$id' and cl_tipo_material_id ='10' ");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $item_descricao = utf8_encode($linha['cl_item_descricao']);
                                            $quantidade = utf8_encode($linha['cl_quantidade_orcada']);
                                    ?>
                                            <tr>
                                                <td><?= $item_descricao; ?></td>
                                                <td><?= $quantidade; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <table class="mb-2">
                                <thead>
                                    <tr>
                                        <th colspan="3">Material - Uso e Consumo</th>
                                    </tr>
                                    <tr>
                                        <td>Descrição</td>
                                        <td>Quantidade</td>
                                        <td>Quantidade Utilizado</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_infraestrutura_os WHERE cl_servico_destinado_id = '$id' and cl_tipo_material_id ='9'  ");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $item_descricao = utf8_encode($linha['cl_item_descricao']);
                                            $quantidade = utf8_encode($linha['cl_quantidade_orcada']);
                                    ?>
                                            <tr>
                                                <td><?= $item_descricao; ?></td>
                                                <td><?= $quantidade; ?></td>
                                                <td></td>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php $resultados_equipe = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_equipe_servico WHERE cl_servico_id = '$id'  ");
                    if ($resultados_equipe > 0) {
                    ?>
                        <tr>
                            <td colspan="5">
                                <table class="mb-2">
                                    <thead>
                                        <tr>
                                            <th colspan="5">Equipe</th>
                                        </tr>
                                        <tr>
                                            <td>Nome</td>
                                            <td>Função</td>
                                            <td>Matriculo</td>
                                            <td>Data Inicio</td>
                                            <td>Data Fim</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($resultados_equipe) {
                                            foreach ($resultados_equipe as $linha) {
                                                $nome_membro = utf8_encode($linha['cl_nome']);
                                                $matricula_membro = utf8_encode($linha['cl_matricula']);
                                                $funcao_membro = utf8_encode($linha['cl_funcao']);
                                                $data_inicio_membro = formatDateB($linha['cl_data_inicio']);
                                                $data_fim_membro = formatDateB($linha['cl_data_fim']);
                                        ?>
                                                <tr>
                                                    <td><?= $nome_membro; ?></td>
                                                    <td><?= $funcao_membro; ?></td>
                                                    <td><?= $matricula_membro; ?></td>
                                                    <td><?= $data_inicio_membro; ?></td>
                                                    <td><?= $data_fim_membro; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>

            </table>
        </div>




        <div class="row center-text assinatura">
            <div class="col">
                <hr>
                <p class="mb-0">Assinatura do Responsável</p>
                <p class="mb-4" style="font-size:0.7em"><?= $cidade_empresa . " - " .
                                                            $estado_empresa ?></p>
                <div class="qr-code-container">
                    <img src="data:image/png;base64,<?php echo base64_encode($imageData); ?>" alt="QR Code">
                </div>

            </div>
        </div>

    </div>

    <script>
        // window.onload = function() {
        //     window.print();
        // };
    </script>

    <!-- Adicione o link para o Bootstrap JS e jQuery (opcional) no final do documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>