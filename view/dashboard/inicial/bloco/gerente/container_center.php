<?php
include "../../../../../modal/dashboard/inicial/gerenciar_dashboard.php";
?>

<div class="row   p-1">
    <div class="col-sm m-1  mb-1 " id="card-receita">
        <div id="card-top-1-1" class="card  border-0 Regular shadow">
            <div id="card_receita" class="card-header">
                Receita
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title"><?php echo  real_format($receita_total); ?></h5>
                    </div>
                    <div class="col-4 text-center">
                        <i class="bi btn btn-outline-light bi-graph-up-arrow "></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm m-1  mb-1 " id="card-despesa">
        <div id="card-top-1-2" class="card  border-0 Regular shadow">
            <div id="card_despesa" class="card-header">
                Despesa
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title"><?php echo real_format($despesa_total); ?></h5>
                    </div>

                    <div class="col-4 text-center">
                        <i class="bi btn btn-outline-light bi-graph-down-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm m-1  mb-1 " id="card-caixa-diario">
        <div title="O Caixa Diário é composto pelas formas de pagamento associadas à conta financeira da Caixa, somado ao saldo inicial do período anterior e ao saldo atual" id="card-top-1-3" class="card  border-0 Regular shadow">
            <div class="card-header">
                Caixa Diário
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8 ">
                        <h5 class="card-title"><?php echo real_format($valor_caixa_total); ?></h5>
                    </div>
                    <div class="col-4 text-center">
                        <i class="bi bi-bag-fill btn btn-outline-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm m-1 mb-1 " id="vendas">
        <div id="card-top-1-4" class="card  border-0 Regular shadow">
            <div class="card-header">
                Vendas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-8  ">
                        <h5 class="card-title"><?php echo real_format($valor_total_venda); ?></h5>
                    </div>
                    <div class="col-4 text-center">
                        <i class="bi bi-cart-check btn btn-outline-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row m-1 mb-3">
    <div class="col-md p-0 m-1 border-0" id="lucro-periodo">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard  border-0">
                <h6> Lucro por Periodo</h6>
            </div>


            <div class="card-body">
                <canvas id="myChart-1"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md p-0 m-1 border-0" id="analise-vendas">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard   border-0">
                <h6>Análise de Vendas</h6>
            </div>
            <div class="card-body">
                <canvas id="myChart-2"></canvas>
            </div>
        </div>
    </div>
</div>

<?php if ($consultar_sistema_delivery == "S") { ?>
    <!-- perguntas resevados ao ecommerce -->
    <div class="row m-1 ">
        <div class="col-md p-0 m-1 border-0">
            <div class="card border-0 shadow">
                <div class="card-header header-card-dashboard  border-0">
                    <h6> Perguntas dos Clientes (<?= $qtd_perguntas_clientes;?>)</h6>
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

<div class="row m-1 mb-3">
    <div class="col-md p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard  border-0">
                <h6> Comparação de Receita anual</h6>
            </div>


            <div class="card-body">
                <canvas id="myChart-3"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard   border-0">
                <h6> Comparação de Despesa Anual</h6>

            </div>
            <div class="card-body">
                <canvas id="myChart-4"></canvas>
            </div>
        </div>
    </div>
</div>



<div class="row m-1 ">
    <div class="col-md p-0 m-1 border-0">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard  border-0">
                <h6> Contas a Receber</h6>
            </div>
            <div class="card-body ">
                <div class="tabela-dashboard">
                    <?php if ($qtd_consultar_contas_a_receber > 0) { ?>
                        <table class="table table-hover">
                            <thead>

                                <tr>
                                    <th scope="col">Dt. Vencimento</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Atraso</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $valor_total = 0;
                                while ($linhas = mysqli_fetch_assoc($consultar_contas_a_receber)) {

                                    $data_vencimento = $linhas['cl_data_vencimento'];
                                    $parceiro = utf8_encode($linhas['cl_razao_social']);
                                    $valor = $linhas['cl_valor_liquido'];
                                    $atraso = $linhas['atraso'];
                                    if ($atraso > 0) {
                                        $atraso = $atraso . " Dia(s)";
                                    } else {
                                        $atraso = null;
                                    }
                                    $valor_total = $valor + $valor_total;
                                ?>
                                    <tr>
                                        <td><?php echo formatDateB($data_vencimento); ?></td>
                                        <td><?php echo $parceiro; ?></td>
                                        <td><?php echo real_format($valor); ?></td>
                                        <td><?php echo ($atraso); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <th scope="col">Total</th>
                                <th scope="col"></th>
                                <th scope="col"><?php echo real_format($valor_total) ?></th>
                                <th scope="col"></th>
                            </tfoot>
                        </table>
                    <?php } else {
                        echo '<div class="sem_registro"><img class="img-fluid img_sem_registro" src="img/sem_registro.svg"> </div>';
                    } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md m-1 p-0 ">
        <div class="card border-0 shadow">
            <div class="card-header header-card-dashboard  border-0">
                <h6>Contas a Pagar </h6>
            </div>
            <div class="card-body">
                <div class="tabela-dashboard">
                    <?php if ($qtd_consultar_contas_a_pagar > 0) { ?>
                        <table class="table table-hover">
                            <thead>

                                <tr>
                                    <th scope="col">Dt Vencimento</th>
                                    <th scope="col">Fornecedor</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Atraso</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $valor_total = 0;
                                while ($linhas = mysqli_fetch_assoc($consultar_contas_a_pagar)) {
                                    $data_vencimento = $linhas['cl_data_vencimento'];
                                    $parceiro = utf8_encode($linhas['cl_razao_social']);
                                    $valor = $linhas['cl_valor_liquido'];
                                    $atraso = $linhas['atraso'];
                                    if ($atraso > 0) {
                                        $atraso = $atraso . " Dia(s)";
                                    } else {
                                        $atraso = null;
                                    }
                                    $valor_total = $valor + $valor_total;
                                ?>
                                    <tr>
                                        <td><?php echo formatDateB($data_vencimento); ?></td>
                                        <td><?php echo $parceiro; ?></td>
                                        <td><?php echo real_format($valor); ?></td>
                                        <td><?php echo $atraso; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <th scope="col">Total</th>
                                <th scope="col"></th>
                                <th scope="col"><?php echo real_format($valor_total) ?></th>
                                <th scope="col"></th>

                            </tfoot>
                        </table>

                    <?php } else {

                        echo '<div class="sem_registro"><img class="img-fluid img_sem_registro" src="img/sem_registro.svg"> </div>';
                    } ?>

                </div>
            </div>
        </div>

    </div>
</div>


<script>
    // Obter o ano atual
    var ano_anterior = <?php echo date('Y') - 1; ?>;
    var ano_atual = <?php echo date('Y'); ?>;


    var receita = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consultar_receita_anual_detalhado($i, $ano) . "',";
        }
        ?>
    ];
    var despesa = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consultar_despesa_anual_detalhado($i, $ano) . "',";
        }
        ?>
    ];

    var receita_anual_atual = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consultar_receita_anual_detalhado($i, $ano) . "',";
        }
        ?>
    ];
    var receita_anual_anterior = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consultar_receita_anual_anterior_detalhado($i, $ano) . "',";
        }
        ?>
    ];
    var despesa_anual_atual = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consultar_despesa_anual_detalhado($i, $ano) . "',";
        }
        ?>
    ];
    var despesa_anual_anterior = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consultar_despesa_anual_anterior_detalhado($i, $ano) . "',";
        }
        ?>
    ];
    var quantidade_anul_vendas = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consulta_quantidade_venda_anual($i, $ano) . "',";
        }
        ?>
    ];
    var valor_anul_vendas = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            echo       "'" . consulta_valor_venda_anual($i, $ano) . "',";
        }
        ?>
    ];
</script>
<script src="js/dashboard/inicial/bloco/gerente/container_center.js"></script>