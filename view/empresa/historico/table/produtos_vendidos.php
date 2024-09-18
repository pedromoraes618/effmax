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

                        <th scope="col">Nº</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Vendedor</th>
                        <th scope="col">Und</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Vlr Unitario</th>
                        <th scope="col">Vlr Total</th>
                        <th scope="col">Status</th>
                 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
              
                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $data_movimento = $linha['cl_data_movimento'];
                        $serie_nf = utf8_encode($linha['cl_serie_nf']);
                        $numero_nf = $linha['numeronota'];
                        $descricao_item = utf8_encode($linha['cl_descricao_item']);
                        $quantidade = $linha['cl_quantidade'];
                        $unidade = $linha['cl_unidade'];
                        $valor_unitario = $linha['cl_valor_unitario'];
                        $vendedor = $linha['vendedor'];
                        
                        $statusitem = $linha['statusitem'];
                        $valor_total = $linha['cl_valor_total'];

                        
                        $total += $valor_total;

                        if ($statusitem == "3") {
                            $statusitem = '<span class="badge rounded-pill text-bg-danger">Cancelado</span>';
                        } else {
                            $statusitem = "";
                        }
                    ?>
                        <tr>
                            <td> <?php echo formatDateB($data_movimento); ?></td>

                            <td><?php echo ($serie_nf . $numero_nf); ?></td>
                            <td><?php echo $descricao_item; ?></td>
                            <td><?php echo $vendedor; ?></td>
                            <td><?php echo $unidade; ?></td>
                            <td><?php echo $quantidade; ?></td>
                            <td><?php echo real_format($valor_unitario); ?></td>
                            <td><?php echo real_format($valor_total); ?></td>
                            <td><?php echo ($statusitem); ?></td>
                        </tr>

                    <?php } ?>
                <tfoot>
                    <tr>
                        <th scope="col" colspan="7">Total</th>
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