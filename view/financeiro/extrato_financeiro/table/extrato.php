<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/extrato_financeiro/gerenciar_extrato_financeiro.php";

?>

<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Extrato financeiro</h6>
    </div>
    <div class="card-body table-responsive">
        <?php
        if ($qtd_consulta_extrato_financeiro > 0) {
        ?>
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th scope="col">Data Pagamento</th>
                        <th scope="col">Doc</th>
                        <th scope="col">Cliente / Fornecedor</th>
                        <th scope="col">Forma pgt</th>
                        <th scope="col">Classificação</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Saldo inicial</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        <td><?php echo  real_format($saldo_inical); ?></td>
                    </tr>
                    <?php
                    $valor_receita = 0;
                    while ($linha = mysqli_fetch_assoc($consulta_extrato_financeiro)) {
                        $data_recebimento = $linha['cl_data_pagamento'];
                        $documento = utf8_encode($linha['cl_documento']);
                        $cliente = utf8_encode($linha['cl_razao_social']);
                        $forma_pagamento = utf8_encode($linha['formapg']);
                        $classificao_financeira = utf8_encode($linha['classificacaofin']);
                        $tipo_lancamento = ($linha['cl_tipo_lancamento']);
                        $valor_liquido = ($linha['cl_valor_liquido']);

                        if ($tipo_lancamento == "DESPESA") {
                            $tipo_lancamento = "<p style='color:red;margin:0px'>Despesa</p>";
                            $valor_receita = $valor_receita - $valor_liquido;
                        } else {
                            $tipo_lancamento =  "<p style='color:green;margin:0px'>Receita</p>";
                            $valor_receita = $valor_receita + $valor_liquido;
                        }
                    ?>
                        <tr>
                            <td><?php echo formatDateB($data_recebimento); ?></td>
                            <td><?php echo $documento;  ?></td>
                            <td><?php echo $cliente; ?></td>
                            <td><?php echo $forma_pagamento; ?></td>
                            <td><?php echo $classificao_financeira; ?></td>
                            <td><?php echo $tipo_lancamento; ?></td>
                            <td><?php echo real_format($valor_liquido); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th><?php echo real_format($valor_receita + $saldo_inical); ?></td>

                    </tr>
                </tfoot>
            </table>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Classificação</th>
                        <th scope="col">Valor Recebido</th>
                        <th scope="col">Valor Pago</th>

                </thead>
                <tbody>
                    <?php
                    $valor_total_pago = 0;
                    $valor_total_recebido = 0;
                    while ($linha = mysqli_fetch_assoc($consulta_extrato_financeiro_classificacao)) {

                        $classificao_financeira = utf8_encode($linha['classificacaofin']);
                        $valor_recebido = ($linha['total_receita']);
                        $valor_pago = ($linha['total_despesa']);


                        $valor_total_recebido += $valor_recebido;
                        $valor_total_pago += $valor_pago;

                    ?>
                        <tr>
                            <td><?php echo ($classificao_financeira); ?></td>
                            <td><?php echo real_format($valor_recebido);  ?></td>
                            <td><?php echo real_format($valor_pago); ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
             
            </table>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Forma Pgt</th>
                        <th scope="col">Valor Recebido</th>
                        <th scope="col">Valor Pago</th>

                </thead>
                <tbody>
                    <?php
                    $valor_total_pago = 0;
                    $valor_total_recebido = 0;
                    while ($linha = mysqli_fetch_assoc($consulta_extrato_financeiro_forma_pagamento)) {

                        $classificao_financeira = utf8_encode($linha['formapagamento']);
                        $valor_recebido = ($linha['total_receita']);
                        $valor_pago = ($linha['total_despesa']);


                        $valor_total_recebido += $valor_recebido;
                        $valor_total_pago += $valor_pago;

                    ?>
                        <tr>
                            <td><?php echo ($classificao_financeira); ?></td>
                            <td><?php echo real_format($valor_recebido);  ?></td>
                            <td><?php echo real_format($valor_pago); ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
             
            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>