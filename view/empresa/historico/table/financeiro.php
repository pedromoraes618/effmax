<?php
include "../../../../modal/empresa/historico/gerenciar_historico.php";
?>

<div class="card m-1  card-dashboard position-relative shadow border-0 mb-2 ">
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
                        <th scope="col">Dt Vencimento</th>
                        <th scope="col">Dt Pagamento</th>
                        <th scope="col">Doc</th>
                        <th scope="col">Forma pgto</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Status</th>
                        <th scope="col">Vlr liquido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $a_receber = 0;
                    $a_pagar = 0;
                    $recebido = 0;
                    $pago = 0;
                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $data_vencimento = $linha['cl_data_vencimento'];
                        $data_pagamento = $linha['cl_data_pagamento'];
                        $documento = utf8_encode($linha['cl_documento']);
                        $cliente = utf8_encode($linha['cl_razao_social']);
                        $parceiro_id = ($linha['cl_parceiro_id']);
                        $forma_pagamento = utf8_encode($linha['formapg']);
                        $status_id = ($linha['cl_status_id']);
                        $status = ($linha['status']);
                        $classificao_financeira = utf8_encode($linha['classificacaofin']);
                        $tipo_lancamento = ($linha['cl_tipo_lancamento']);
                        $valor_liquido = ($linha['cl_valor_liquido']);
                        $valor_juros = ($linha['cl_juros']);
                        $juros = ($linha['cl_juros']);
                        $valor_bruto = ($linha['cl_valor_bruto']);

                        if ($tipo_lancamento == "RECEITA") {
                            $tipo_lancamento = '<span class="badge rounded-pill text-bg-success">Receita</span>';
                        } else {
                            $tipo_lancamento = '<span class="badge rounded-pill text-bg-danger">Despesa</span>';
                        }
                        if ($status_id == "1") { //a receber
                            $a_receber += $valor_liquido;
                        } elseif ($status_id == "3") { //a pagar
                            $a_pagar += $valor_liquido;
                        } elseif ($status_id == "2") { //recebido
                            $recebido += $valor_liquido;
                        } elseif ($status_id == "4") { //pago
                            $pago += $valor_liquido;
                        }

                    ?>
                        <tr>
                            <td> <?php echo formatDateB($data_vencimento); ?></td>
                            <td><?php echo formatDateB($data_pagamento); ?></td>
                            <td><?php echo ($documento); ?></td>
                            <td><?php echo $forma_pagamento; ?></td>
                            <td><?php echo $tipo_lancamento; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo real_format($valor_liquido); ?></td>
                        </tr>

                    <?php } ?>
                <tfoot>
                    <tr>
                        <th scope="col" colspan="6">A Receber</th>
                        <th scope="col"><?php echo  real_format($a_receber); ?></th>
                    </tr>
                    <tr>
                        <th scope="col" colspan="6">Recebido</th>
                        <th scope="col"><?php echo  real_format($recebido); ?></th>
                    </tr>
                    <tr>
                        <th scope="col" colspan="6">A Pagar</th>
                        <th scope="col"><?php echo  real_format($a_pagar); ?></th>
                    </tr>
                    <tr>
                        <th scope="col" colspan="6">Pago</th>
                        <th scope="col"><?php echo  real_format($pago); ?></th>
                    </tr>
                </tfoot>
                </tbody>


            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>