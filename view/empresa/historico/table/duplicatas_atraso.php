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

                    while ($linha = mysqli_fetch_assoc($consulta)) {
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
                <tfoot>
                    <tr>
                        <th scope="col" colspan="4">Total</th>
                        <th scope="col"><?= real_format($total); ?></th>
                    </tr>

                </tfoot>
                </tbody>


            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>