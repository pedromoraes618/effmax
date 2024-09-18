<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/caixa/movimento_caixa/gerenciar_movimento_caixa.php";

?>

<div class="card-print">
    <div class="card m-1  shadow border-0 mb-2 ">
        <div class="card-header header-card-dashboard" title="Receitas consistem em lançamentos que foram feitos atraveis de recebimento">
            <h6><i class="bi bi-exclamation-octagon"></i> Movimento Geral Receita</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Data Movimento</th>
                        <th scope="col">Nº</th>
                        <th scope="col">Doc</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Forma Pgto</th>
                        <th scope="col">Vlr liquido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $valor_receita = 0;
                    $valor_outras_receitas = 0;
                    $valor_outras_despesas = 0;
                    while ($linha = mysqli_fetch_assoc($consultar_receita)) {
                        $data_recebimento = $linha['cl_data_pagamento'];
                        $documento = utf8_encode($linha['cl_documento']);
                        $serie_nf = utf8_encode($linha['cl_serie_nf']);
                        $numero_nf = utf8_encode($linha['cl_numero_nf']);
                        $cliente = utf8_encode($linha['cl_razao_social']);
                        $forma_pagamento = utf8_encode($linha['formapg']);
                        $valor_liquido = ($linha['cl_valor_liquido']);
                        $valor_receita = $valor_liquido + $valor_receita;
                    ?>
                        <tr>
                            <td><?php echo formatDateB($data_recebimento); ?></td>
                            <td><?php echo $serie_nf . " " . $numero_nf; ?></td>
                            <td><?php echo $documento; ?></td>
                            <td><?php echo $cliente; ?></td>
                            <td><?php echo $forma_pagamento; ?></td>
                            <td><?php echo real_format($valor_liquido); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total</th>
                        <th><?php echo real_format($valor_receita); ?></td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php if (mysqli_num_rows($consultar_outras_receita) > 0) { ?>
        <div class="card m-1 shadow border-0 mb-2 ">
            <div class="card-header header-card-dashboard " title="Outras Receitas consistem em lançamentos avulsos">
                <h6><i class="bi bi-exclamation-octagon"></i> Movimento Geral Outras Receitas</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Data movimento</th>
                            <th scope="col">Doc</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Forma Pgto</th>
                            <th scope="col">Vlr liquido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $valor_outras_receitas = 0;
                        while ($linha = mysqli_fetch_assoc($consultar_outras_receita)) {
                            $data_recebimento = $linha['cl_data_pagamento'];
                            $documento = utf8_encode($linha['cl_documento']);
                            $cliente = utf8_encode($linha['cl_razao_social']);
                            $forma_pagamento = utf8_encode($linha['formapg']);
                            $valor_liquido = ($linha['cl_valor_liquido']);
                            $valor_outras_receitas += $valor_liquido;
                        ?>
                            <tr>
                                <td><?php echo formatDateB($data_recebimento); ?></td>
                                <td><?php echo $documento;  ?></td>
                                <td><?php echo $cliente; ?></td>
                                <td><?php echo $forma_pagamento; ?></td>
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
                            <th><?php echo real_format($valor_outras_receitas); ?></td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php } ?>
    <div class="card m-1 shadow border-0 mb-2 ">
        <div class="card-header header-card-dashboard " title="Receitas consistem em lançamentos que foram feitos atraveis de provisionamentos">
            <h6><i class="bi bi-exclamation-octagon"></i> Movimento Geral Despesa</h6>
        </div>
        <div class="card-body table-responsive">
            <div class="card-body p-1">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Data movimento</th>
                            <th scope="col">Doc</th>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">Forma Pgto</th>
                            <th scope="col">Vlr liquido</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                        $valor_despesa = 0;
                        while ($linha = mysqli_fetch_assoc($consultar_despesa)) {
                            $data_recebimento = $linha['cl_data_pagamento'];
                            $documento = utf8_encode($linha['cl_documento']);
                            $cliente = utf8_encode($linha['cl_razao_social']);
                            $forma_pagamento = utf8_encode($linha['formapg']);
                            $valor_liquido = ($linha['cl_valor_liquido']);
                            $valor_despesa += $valor_liquido;
                        ?>
                            <tr>
                                <td><?php echo formatDateB($data_recebimento); ?></td>
                                <td><?php echo $documento;  ?></td>
                                <td><?php echo $cliente; ?></td>
                                <td><?php echo $forma_pagamento; ?></td>
                                <td><?php echo real_format($valor_liquido); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th><?php echo real_format($valor_despesa); ?></td>

                            </tr>
                        </tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php if (mysqli_num_rows($consultar_outras_despesa) > 0) { ?>
        <div class="card m-1 shadow border-0 mb-2 ">
            <div class="card-header header-card-dashboard " title="Outras Despesas consistem em lançamentos avulsos">
                <h6><i class="bi bi-exclamation-octagon"></i> Movimento Geral Outras Despesas</h6>
            </div>
            <div class="card-body table-responsive">
                <div class="card-body p-1">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Data movimento</th>
                                <th scope="col">Doc</th>
                                <th scope="col">Fornecedor</th>
                                <th scope="col">Forma Pgto</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $valor_outras_despesas = 0;
                            while ($linha = mysqli_fetch_assoc($consultar_outras_despesa)) {
                                $data_recebimento = $linha['cl_data_pagamento'];
                                $documento = utf8_encode($linha['cl_documento']);
                                $cliente = utf8_encode($linha['cl_razao_social']);
                                $forma_pagamento = utf8_encode($linha['formapg']);
                                $valor_liquido = ($linha['cl_valor_liquido']);
                                $valor_outras_despesas += $valor_liquido;
                            ?>
                                <tr>
                                    <td><?php echo formatDateB($data_recebimento); ?></td>
                                    <td><?php echo $documento;  ?></td>
                                    <td><?php echo $cliente; ?></td>
                                    <td><?php echo $forma_pagamento; ?></td>
                                    <td><?php echo real_format($valor_liquido); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th><?php echo real_format($valor_outras_despesas); ?></td>

                                </tr>
                            </tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="card m-1 shadow border-0 mb-2 ">
        <div class="card-header header-card-dashboard ">
            <h6><i class="bi bi-exclamation-octagon"></i> Total</h6>
        </div>
        <div class="card-body table-responsive">
            <div class="card-body p-1">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" colspan="6">Saldo Inicial</th>
                            <th><?php echo real_format($saldo_inical); ?></th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="6">Receita + </th>
                            <th><?php echo real_format($valor_receita); ?></th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="6">Outras Receitas + </th>
                            <th><?php echo real_format($valor_outras_receitas); ?></th>
                        </tr>
                        <tr>
                            <th scope="col" colspan="6">Despesa -</th>
                            <th><?php echo real_format($valor_despesa); ?></td>
                        </tr>
                        <tr>
                            <th scope="col" colspan="6">Outras Despesas -</th>
                            <th><?php echo real_format($valor_outras_despesas); ?></td>
                        </tr>
                        <tr>
                            <th scope="col" colspan="6">Total</th>
                            <th><?php echo real_format($saldo_inical + $valor_receita + $valor_outras_receitas - $valor_despesa - $valor_outras_despesas); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>