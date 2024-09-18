<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/caixa/movimento_caixa/gerenciar_movimento_caixa.php";

?>
<div class="card-print">
    <div class="card m-1 shadow border-0 mb-2 ">
        <div class="card-header header-card-dashboard ">
            <h6><i class="bi bi-exclamation-octagon"></i> Vendas</h6>
        </div>
        <div class="card-body p-1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Data movimento</th>
                        <th scope="col">Doc</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Vendedor</th>
                        <th scope="col">Forma pgto</th>
                        <th scope="col">Vlr liquido</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $valor_total = 0;
                    while ($linha = mysqli_fetch_assoc($consultar_vendas_fpg)) {
                        $data_movimento = $linha['cl_data_movimento'];
                        $serie_nf = ($linha['cl_serie_nf']);
                        $numero_nf = ($linha['cl_numero_nf']);
                        $cliente = utf8_encode($linha['cl_razao_social']);
                        $forma_pagamento = utf8_encode($linha['formapg']);
                        $valor_liquido = ($linha['cl_valor_liquido']);
                        $status_recebimento = ($linha['cl_status_recebimento']);
                        $vendedor = ($linha['vendedor']);
                        $valor_total = $valor_liquido + $valor_total;

                        if ($status_recebimento == "1") {
                            $status_recebimento = "<p style='color:red;margin:0px'>Pendente</p>";
                        } else {
                            $status_recebimento =  "<p style='color:green;margin:0px'>Recebido</p>";
                        }
                    ?>
                        <tr>
                            <td><?php echo formatDateB($data_movimento); ?></td>
                            <td><?php echo $serie_nf . "" . $numero_nf;  ?></td>
                            <td><?php echo $cliente; ?></td>
                            <td><?php echo $vendedor; ?></td>
                            <td><?php echo $forma_pagamento; ?></td>
                            <td><?php echo real_format($valor_liquido); ?></td>
                            <td><?php echo ($status_recebimento); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th><?php echo real_format($valor_total); ?></td>
                        <th></th>

                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
    <?php if ($qtd_consultar_devolucao_Venda_fpg > 0) { ?>
        <div class="card m-1 shadow border-0 mb-2 ">
            <div class="card-header header-card-dashboard ">
                <h6><i class="bi bi-exclamation-octagon"></i> Devolução / Estorno</h6>
            </div>
            <div class="card-body p-1">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Data movimento</th>
                            <th scope="col">Doc</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Forma pgto</th>
                            <th scope="col">Vlr liquido</th>
                            <th scope="col">Referente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $valor_total = 0;
                        while ($linha = mysqli_fetch_assoc($consultar_devolucao_Venda_fpg)) {
                            $data_movimento = $linha['cl_data_movimento'];
                            $serie_nf = ($linha['cl_serie_nf']);
                            $numero_nf = ($linha['cl_numero_nf']);
                            $cliente = utf8_encode($linha['cl_razao_social']);
                            $forma_pagamento = utf8_encode($linha['formapg']);
                            $valor_liquido = ($linha['cl_valor_liquido']);
                            $numero_nf_devolucao = ($linha['cl_numero_nf_devolucao']);
                            $valor_total = $valor_liquido + $valor_total;

                            if ($status_recebimento == "1") {
                                $status_recebimento = "<p style='color:red;margin:0px'>Pendente</p>";
                            } else {
                                $status_recebimento =  "<p style='color:green;margin:0px'>Recebido</p>";
                            }
                        ?>
                            <tr>
                                <td><?php echo formatDateB($data_movimento); ?></td>
                                <td><?php echo $serie_nf . "" . $numero_nf;  ?></td>
                                <td><?php echo $cliente; ?></td>
                                <td><?php echo $forma_pagamento; ?></td>
                                <td><?php echo real_format($valor_liquido); ?></td>
                                <td><?php echo ($numero_nf_devolucao); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th><?php echo real_format($valor_total); ?></td>
                            <th></th>

                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    <?php } ?>

    <div class="card m-1 shadow border-0 mb-2 ">
        <div class="card-header header-card-dashboard ">
            <h6><i class="bi bi-exclamation-octagon"></i> Resumo</h6>
        </div>
        <div class="card-body p-1">
            <table class="table table-hover">
                <tbody>
                    <?php
                    $valor_total = 0;
                    while ($linha = mysqli_fetch_assoc($consultar_resumo_vendas_fpg)) {
                        $forma_pagamento = utf8_encode($linha['formapg']);
                        $valor_liquido = ($linha['valorliquido']);

                        $valor_total = $valor_liquido + $valor_total;
                    ?>
                        <tr>
                            <th colspan="5"><?php echo ($forma_pagamento); ?></th>
                            <th><?php echo real_format($valor_liquido); ?></th>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total</th>
                        <th><?php echo real_format($valor_total); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>