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
                        <th scope="col">Dt Emissão</th>
                        <th scope="col">Dt Entrada</th>
                        <th scope="col">Doc</th>

                        <th scope="col">Forma Pgto</th>
                        <th scope="col">Status</th>
                        <th scope="col">Vlr liquido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;

                    while ($linha = mysqli_fetch_assoc($consulta)) {

                        $data_entrada = ($linha['cl_data_entrada']);
                        $data_emissao = ($linha['cl_data_emissao']);
                        $serie_nf = ($linha['cl_serie_nf']);
                        $numero_nf = ($linha['cl_numero_nf']);
                        $formapgt = utf8_encode($linha['formapgt']);
                        $valor_total_nota = ($linha['cl_valor_total_nota']);
                        $status_nf = ($linha['cl_status_nf']);
                        $status_provisionamento = ($linha['cl_status_provisionamento']);
                        if ($status_nf == "1") {
                            $status = "<span class='badge rounded-pill text-bg-success'>Concluida</span>";
                        } elseif ($status_nf == "2") {
                            $status = "<span class='badge rounded-pill text-bg-primary'>Em andamento</span>";
                        } elseif ($status_nf == "3") {
                            $status = "<span class='badge rounded-pill text-bg-danger'>Cancelada</span>";
                        }
                        $total += $valor_total_nota;
                    ?>
                        <tr>
                            <td> <?php echo formatDateB($data_entrada); ?></td>
                            <td> <?php echo formatDateB($data_emissao); ?></td>

                            <td><?php echo ($serie_nf . $numero_nf); ?></td>
                            <td><?php echo $formapgt; ?></td>
                           
                            <td><?php echo $status; ?></td>
                            <td><?php echo real_format($valor_total_nota); ?></td>
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