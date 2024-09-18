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
                        <th scope="col">Dt Movimento</th>

                        <th scope="col">Doc</th>
                        <th scope="col">Forma Pgto</th>
                        <th scope="col">Vendedor</th>
                        <th scope="col">Status</th>
                        <th scope="col">Vlr Liquido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
              
                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $data_movimento = $linha['cl_data_movimento'];
                        $valor_liquido = $linha['cl_valor_liquido'];
                        $formapgt = utf8_encode($linha['formapgt']);
                        $serie_nf = utf8_encode($linha['cl_serie_nf']);
                        $numero_nf = $linha['cl_numero_nf'];
                        $vendedor = utf8_encode($linha['vendedor']);
                        $status_venda = ($linha['cl_status_venda']);

                        $total += $valor_liquido;

                        if ($status_venda == "3") {
                            $status_venda = '<span class="badge rounded-pill text-bg-danger">Cancelado</span>';
                        } else {
                            $status_venda = "";
                        }
                    ?>
                        <tr>
                            <td> <?php echo formatDateB($data_movimento); ?></td>

                            <td><?php echo ($serie_nf . $numero_nf); ?></td>
                            <td><?php echo $formapgt; ?></td>
                            <td><?php echo $vendedor; ?></td>
                            <td><?php echo $status_venda; ?></td>
                            <td><?php echo real_format($valor_liquido); ?></td>
                        </tr>

                    <?php } ?>
                <tfoot>
                    <tr>
                        <th scope="col" colspan="5">Total</th>
                        <th scope="col"><?php echo  real_format($total); ?></th>
                    </tr>

                </tfoot>
                </tbody>


            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>