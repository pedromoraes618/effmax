<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/pagamentos_recebimentos/gerenciar_pagamentos_recebimentos.php";

if (isset($_GET['ordem'])) {
    $ordem = $_GET['ordem'];
}
?>

<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> <?php echo $titulo; ?></h6>
    </div>
    <div class="card-body table-responsive">
        <?php
        if ($qtd_consulta > 0) {
        ?>
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th scope="col">Data Vencimento</th>
                        <th scope="col">Data Pagamento</th>
                        <th scope="col">Doc</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Forma pgto</th>
                        <th scope="col">Classificação</th>
                        <th scope="col">Atraso</th>
                        <th scope="col">Bruto</th>
                        <th scope="col">Juros</th>
                        <th scope="col">Vlr liquido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inicializar as variáveis
                    $cliente_id_anterior = null; // Variável para rastrear o cliente anterior
                    $subtotalBruto = 0; // Subtotal para cada cliente
                    $subtotalLJuros = 0; // Subtotal para cada cliente
                    $subtotalLiquido = 0; // Subtotal para cada cliente

                    $total_bruto = 0;
                    $totalLJuros = 0;
                    $totalLiquido = 0;

                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $data_vencimento = $linha['cl_data_vencimento'];
                        $data_pagamento = $linha['cl_data_pagamento'];
                        $documento = utf8_encode($linha['cl_documento']);
                        $cliente = utf8_encode($linha['cl_razao_social']);
                        $parceiro_id = ($linha['cl_parceiro_id']);
                        $forma_pagamento = utf8_encode($linha['formapg']);
                        $status = ($linha['cl_status_id']);
                        $classificao_id = utf8_encode($linha['cl_classificacao_id']);
                        $classificao_financeira = utf8_encode($linha['classificacaofin']);
                        $tipo_lancamento = ($linha['cl_tipo_lancamento']);
                        $valor_liquido = ($linha['cl_valor_liquido']);
                        $valor_juros = ($linha['cl_juros']);
                        $juros = ($linha['cl_juros']);
                        $valor_bruto = ($linha['cl_valor_bruto']);
                        $atraso = ($linha['atraso']);
                        if ($atraso > 0) {
                            $atraso = $atraso . " Dia(s)";
                        } else {
                            $atraso = null;
                        }


                        if ($ordem == "1" or $ordem == "0") {
                            $parceiro_id = $parceiro_id;
                        } elseif ($ordem == "2") {
                            $parceiro_id = $classificao_id;
                        } elseif ($ordem == "3") {
                            $parceiro_id = $data_vencimento;
                        }


                        if ($valor_juros == 0 and $taxa_juros != 0 and ($atraso > $qtd_dia_juros) and $status == "1") {
                            $juros = (($taxa_juros / 100) * $valor_bruto);
                            $valor_juros = (real_format(($taxa_juros / 100) * $valor_bruto) . " 'Juros Previstos'"); //
                            $previsao_juros = true;
                        } else {
                            $juros = $juros;
                            $valor_juros = real_format($valor_juros);
                        }


                        // Verificar se o cliente_id atual é diferente do cliente_id anterior
                        if ($cliente_id_anterior !== $parceiro_id) {
                            // Exibir o subtotal acumulado para o cliente anterior, se houver
                            if ($cliente_id_anterior !== null) {
                    ?>
                                <tr class="table-active">
                                    <th scope="col" colspan="7">Sub Total</th>
                                    <th scope="col"><?php echo  real_format($subtotalBruto); ?></th>
                                    <th scope="col"><?php echo  real_format($subtotalLJuros); ?></th>
                                    <th scope="col"><?php echo  real_format($subtotalLiquido); ?></th>

                                </tr>
                        <?php
                            }

                            // Atualizar o cliente_id anterior e redefinir o subtotal
                            $cliente_id_anterior = $parceiro_id;
                            $subtotalBruto = 0;
                            $subtotalLJuros = 0;
                            $subtotalLiquido = 0;
                        }
                        // Adicionar o valor líquido da transação ao subtotal do cliente atual
                        $subtotalBruto += $valor_bruto;
                        $subtotalLJuros += $juros;
                        $subtotalLiquido += $valor_liquido;

                        $total_bruto += $valor_bruto;
                        $totalLJuros += $juros;
                        $totalLiquido += $valor_liquido;
                        ?>
                        <tr>
                            <td><?php echo formatDateB($data_vencimento); ?></td>
                            <td><?php echo formatDateB($data_pagamento); ?></td>
                            <td><?php echo $documento;  ?></td>
                            <td><?php echo $cliente; ?></td>
                            <td><?php echo $forma_pagamento; ?></td>
                            <td><?php echo $classificao_financeira; ?></td>
                            <td style="color: red;"><?php echo $atraso; ?></td>

                            <td><?php echo real_format($valor_bruto); ?></td>
                            <td><?php echo ($valor_juros); ?></td>
                            <td><?php echo real_format($valor_liquido); ?></td>
                        </tr>
                    <?php


                    }
                    // Exibir o subtotal final para o último cliente, se houver transações
                    if ($cliente_id_anterior !== null) {
                    ?>
                        <tr class="table-active">
                            <th scope="col" colspan="7">Sub Total</th>
                            <th scope="col"><?php echo  real_format($subtotalBruto); ?></th>
                            <th scope="col"><?php echo  real_format($subtotalLJuros); ?></th>
                            <th scope="col"><?php echo  real_format($subtotalLiquido); ?></th>

                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <th scope="col" colspan="7">Total</th>
                        <th scope="col"><?php echo  real_format($total_bruto); ?></th>
                        <th scope="col"><?php echo  real_format($totalLJuros); ?></th>
                        <th scope="col"><?php echo  real_format($totalLiquido); ?></th>

                    </tr>
                </tfoot>

            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>