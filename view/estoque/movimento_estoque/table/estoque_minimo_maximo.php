<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/movimento_estoque/gerenciar_movimento.php";
?>

<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Estoque abaixo do minimo</h6>
    </div>
    <div class="card-body table-responsive">
        <?php
        if ($qtd_consulta > 0) {
        ?>
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Und</th>
                        <th class="table-active" scope="col">Mínimo</th>
                        <th class="table-active" scope="col">Disponível</th>
                        <th scope="col" title='Última Compra'>Ult compra</th>
                        <th scope="col">Ult Venda</th>

                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $total_estoque = 0;
                    $total_custo = 0;
                    $total_valor_vendido = 0;
                    while ($linha = mysqli_fetch_assoc($consulta)) {

                        $codigo = ($linha['cl_id']);
                        $produto = utf8_encode($linha['cl_descricao']);
                        $referencia = utf8_encode($linha['cl_referencia']);
                        $unidadem = utf8_encode($linha['unidadem']);
                        $estoque_minimo = $linha['cl_estoque_minimo'];
                        $estoque = $linha['cl_estoque'];
                        $ultima_compra = $linha['ultima_compra'];
                        $ultima_venda = $linha['ultima_venda'];
                        $referencia =  $referencia != '' ? 'Ref ' . $referencia : '';

                    ?>
                        <tr>

                            <td><?php echo "Código: $codigo <br>" . ($produto) . "<br>" . $referencia; ?></th>
                            <td><?php echo ($unidadem); ?></th>
                            <td class="table-active"><?php echo formatarNumero($estoque_minimo); ?></th>
                            <td class="table-active"><?php echo formatarNumero($estoque); ?></th>
                            <td><?php echo formatDateB($ultima_compra); ?></th>
                            <td><?php echo formatDateB($ultima_venda); ?></th>
                                <!-- <td><button type="button" class="btn btn-sm btn-dark" title="Tendência de Produtos"><i class="bi bi-speedometer2"></i></button></td> -->

                        </tr>
                    <?php } ?>
                </tbody>


            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>


<!-- <div class="card m-1  card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Detalhado Por cliente</h6>
    </div>
    <div class="card-body table-responsive">
        <canvas id="detalhado_por_cliente" height="100"></canvas>

    </div>
</div>

<script src="js/faturamento/relatorio_faturamento/table/faturamento_cliente.js"></script> -->