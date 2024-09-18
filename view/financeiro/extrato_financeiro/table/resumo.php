<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/extrato_financeiro/gerenciar_extrato_financeiro.php";

?>

<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Resumo Financeiro</h6>
    </div>
    <div class="card-body table-responsive">
        <?php
        if ($qtd_consulta > 0) {
        ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Data Pagamento</th>
                        <th scope="col">Saldo Anterior</th>
                        <th scope="col">Entrada</th>
                        <th scope="col">Saida</th>
                        <th scope="col">Saldo Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $saldo_anterior = 0;
                    $verifica_saldo = false;
                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $data_pagamento = $linha['data'];
                        $entrada = $linha['entrada'];
                        $saida = $linha['saida'];
                        if ($verifica_saldo == false) {
                            $saldo_anterior = $saldo_inical;
                            $verifica_saldo = true;
                        }
                        
                        // Cálculo do Saldo Final
                        $saldo_final = $saldo_anterior + $entrada - $saida;

                        // Saída da linha da tabela
                    ?>
                        <tr>
                            <td><?php echo formatDateB($data_pagamento); ?></td>
                            <td><?php echo real_format($saldo_anterior); ?></td>
                            <td><?php echo real_format($entrada); ?></td>
                            <td><?php echo real_format($saida); ?></td>
                            <td><?php echo real_format($saldo_final); ?></td>
                        </tr>
                    <?php
                        // Atualize o saldo anterior para o próximo loop
                        $saldo_anterior = $saldo_final;
                    }
                    ?>
                </tbody>
            </table>

        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>