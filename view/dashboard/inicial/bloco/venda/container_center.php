<?php

include "../../../../../modal/dashboard/inicial/gerenciar_dashboard.php";
?>

<div class="row   p-1">
    <div class="col-sm m-1  mb-1 ">
        <div id="card-top-1-1" class="card  border-0 Regular shadow">
            <div id="card_receita" class="card-header">
                Desempenho de Vendas (R$)
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title"><?php echo  real_format($valor_total_minhas_venda); ?></h5>
                    </div>
                    <div class="col-4 text-center">
                        <i class="bi btn btn-outline-light bi-graph-up-arrow "></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm m-1  mb-1 ">
        <div id="card-top-1-2" class="card  border-0 Regular shadow">
            <div id="card_despesa" class="card-header">
                Desempenho de Vendas (qtd)
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title"><?php echo ($qtd_total_minhas_vendas); ?></h5>
                    </div>

                    <div class="col-4 text-center">
                        <i class="bi btn btn-outline-light bi-graph-down-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm m-1  mb-1 ">
        <div id="card-top-1-3" class="card  border-0 Regular shadow">
            <div class="card-header">
                Participação nas Vendas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8 ">

                        <h5 class="card-title"><?php
                                                $minhParticipacaoPorcentagemVendas = ($qtd_total_minhas_vendas / $qtd_total_vendas) * 100;
                                                echo formatarPorcentagem($minhParticipacaoPorcentagemVendas); ?></h5>
                    </div>
                    <div class="col-4 text-center">
                        <i class="bi bi-bag-fill btn btn-outline-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row m-1 mb-3" style="height: 200px;">
    <div class="col-md    p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard  border-0">
                <h6>Minhas vendas</h6>
            </div>


            <div class="card-body">
                <canvas id="myChart-1" style="height: 200px;"></canvas>
            </div>
        </div>
    </div>
    <!-- <div class="col-md p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard   border-0">
                <h6>Faturamento por nota fiscal</h6>
            </div>
            <div class="card-body">
                <canvas id="myChart-2"></canvas>
            </div>
        </div>
    </div> -->

</div>

<?php if ($consultar_sistema_delivery == "S") { ?>
    <!-- perguntas resevados ao ecommerce -->
    <div class="row m-1 ">
        <div class="col-md p-0 m-1 border-0">
            <div class="card border-0 shadow">
                <div class="card-header header-card-dashboard  border-0">
                    <h6> Perguntas dos Clientes (<?= $qtd_perguntas_clientes; ?>)</h6>
                </div>
                <div class="card-body ">
                    <div class="tabela-dashboard">
                        <?php if ($qtd_perguntas_clientes > 0) { ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Mensagem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $valor_total = 0;
                                    while ($linhas = mysqli_fetch_assoc($consultar_perguntas_clientes)) {

                                        $data =  ($linhas['cl_data']);
                                        $nome =  utf8_encode($linhas['cl_nome']);
                                        $mensagem =  utf8_encode($linhas['cl_mensagem']);
                                        $descricao_prd =  utf8_encode($linhas['cl_descricao']);

                                    ?>
                                        <tr>
                                            <td><?= formatarTimeStamp($data); ?></td>
                                            <td><?= $nome; ?></td>
                                            <td><?= $descricao_prd; ?></td>
                                            <td><?= ($mensagem); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                            </table>
                        <?php } else {
                            echo '<div class="sem_registro"><img class="img-fluid img_sem_registro" src="img/sem_registro.svg"> </div>';
                        } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<!-- <div class="row m-1 mb-3">
    <div class="col-md p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard  border-0">
                <h6> Comparação de receita anual</h6>
            </div>


            <div class="card-body">
                <canvas id="myChart-3"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard   border-0">
                <h6> Comparação de despesa anual</h6>

            </div>
            <div class="card-body">
                <canvas id="myChart-4"></canvas>
            </div>
        </div>
    </div>

</div> -->





<script>
    var ano_anterior = <?php echo date('Y') - 1; ?>;
    var ano_atual = <?php echo date('Y'); ?>;

    var valor_minhas_vendas = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consulta_valor_total_minhas_vendas($conecta, $i, $ano, $usuario_id) . "',";
        }
        ?>
    ];
    var qtd_minhas_vendas = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consulta_qtd_total_minhas_vendas($conecta, $i, $ano, $usuario_id) . "',";
        }
        ?>
    ];
</script>
<script src="js/dashboard/inicial/bloco/venda/container_center.js"></script>