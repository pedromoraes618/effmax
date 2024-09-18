<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/movimento_estoque/gerenciar_movimento.php";
?>

<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Movimento do Estoque</h6>
    </div>
    <div class="card-body table-responsive">
        <?php
        if ($qtd_consulta > 0) {
        ?>
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Entrada</th>
                        <th scope="col">Saida</th>
                        <th scope="col">Estoque</th>

                        <!-- <th scope="col">Custo Total</th> -->
                        <th scope="col">Ult Compra</th>
                        <th scope="col">Ult Venda</th>
                        <th scope="col">Dias Sem Mov</th>
                        <th scope="col">Mais Barato</th>
                        <th scope="col">Preço Médio</th>
                        <th scope="col">Mais Caro</th>
                        <th scope="col">Custo</th>
                        <th scope="col">Venda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $total_estoque = 0;
                    $total_custo = 0;
                    $total_valor_vendido = 0;
                    while ($linha = mysqli_fetch_assoc($consulta)) {

                        $codigo = utf8_encode($linha['cl_id']);
                        $produto = utf8_encode($linha['cl_descricao']);
                        $referencia = utf8_encode($linha['cl_referencia']);

                        $estoque = $linha['cl_estoque'];
                        $entrada = $linha['quantidade_entrada'];
                        $saida = $linha['quantidade_saida'];
                        $preco_custo_unit = $linha['cl_preco_custo'];
                        $ultima_compra = $linha['ultima_compra'];
                        $ultima_venda = $linha['ultima_venda'];
                        $valor_vendido = $linha['valor_vendido'];
                        $preco_venda = $linha['cl_preco_venda'];
                        $preco_medio = $linha['preco_medio'];
                        $preco_mais_barato = $linha['preco_mais_barato'];
                        $preco_mais_caro = $linha['preco_mais_caro'];
                        $dias_sem_movimentacao = $linha['dias_sem_movimentacao'];
                        $referencia =  $referencia != '' ? 'Ref ' . $referencia : '';

                        // $valor_venda = $linha['cl_preco_venda'];
                        // $total += $valor_venda;

                        // Atualize os totais
                        $total_estoque += $estoque;
                        $total_custo += $preco_custo_unit * $estoque;
                        $total_valor_vendido += $valor_vendido;
                    ?>
                        <tr>

                            <td><?php echo "Cód: $codigo <br>" . ($produto) . "<br>" . $referencia; ?></th>
                            <td><?php echo formatarNumero($entrada); ?></th>
                            <td><?php echo formatarNumero($saida); ?></th>
                            <td><?php echo formatarNumero($estoque); ?></th>
                                <!-- <td><?php echo real_format($preco_custo_unit * $estoque); ?></th> -->
                            <td><?php echo formatDateB($ultima_compra); ?></th>
                            <td><?php echo formatDateB($ultima_venda); ?></th>
                            <td><?php echo ($dias_sem_movimentacao); ?></th>
                            <td><?php echo real_format($preco_mais_barato); ?></th>
                            <td><?php echo real_format($preco_medio); ?></th>
                            <td><?php echo real_format($preco_mais_caro); ?></th>
                            <td><?php echo real_format($preco_custo_unit); ?></th>
                            <td> <?php echo  real_format($preco_venda); ?></td>
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