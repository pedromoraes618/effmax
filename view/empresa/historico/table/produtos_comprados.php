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
                        <th scope="col">Dt Emissão </th>
                        <th scope="col">Dt Entrada</th>
                        <th scope="col">Código</th>
                        <th scope="col">Doc</th>
                        <th scope="col">Descrição</th>
            
                        <th scope="col">Und</th>
                        <th scope="col">Qtd</th>
                        <th scope="col">Vlr Unitário</th>
                        <th scope="col">Vlr Total</th>
                  
                 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
              
                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $data_entrada = $linha['cl_data_entrada'];
                        $data_emissao = $linha['cl_data_emissao'];
                        $codigo = $linha['codigo'];
                        $serie_nf = utf8_encode($linha['cl_serie_nf']);
                        $numero_nf = $linha['numeronota'];
                        $descricao_item = utf8_encode($linha['item']);
                        $quantidade = $linha['cl_quantidade'];
                        $unidade =utf8_encode($linha['cl_sigla']);
                        $valor_unitario = $linha['vltunit'];
                        $valor_total = $linha['vlrtotal'];
                        $total += $valor_total;

              
                    ?>
                        <tr>
                        
                            <td> <?php echo formatDateB($data_emissao); ?></td>
                            <td> <?php echo formatDateB($data_entrada); ?></td>
                            <td> <?php echo ($codigo); ?></td>
                            <td><?php echo ($serie_nf . $numero_nf); ?></td>
                            <td><?php echo $descricao_item; ?></td>
                            <td><?php echo $unidade; ?></td>
                            <td><?php echo $quantidade; ?></td>
                            <td><?php echo real_format($valor_unitario); ?></td>
                            <td><?php echo real_format($valor_total); ?></td>
                     
                        </tr>

                    <?php } ?>
                <tfoot>
                    <tr>
                        <th scope="col" colspan="8">Total</th>
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