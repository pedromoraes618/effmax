<?php include "../../../../modal/venda/relatorio_atividades/gerenciar_relatorio.php"; ?>

<div class="row">
    <!-- Coluna esquerda com métricas principais (com ícones e valores à esquerda) -->
    <div class="col-md-5">
        <div class="row">
            <!-- Clientes -->
            <div class="col-md-6">
                <div class="metric-box p-2 mb-3  bg-body-tertiary border rounded  shadow-sm">
                    <div class="metric-content text-start">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="text-muted fs-6 mb-0">Vendas</p>
                                <div class="icone rounded border p-1">
                                    <i class="bi bi-cart-check-fill fs-5"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold"><?= real_format($valor_vendido_presente); ?></h5>
                            <small class="text-success"><?= $variacao_vendas_span; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-box p-2 mb-3  bg-body-tertiary border rounded shadow-sm">
                    <div class="metric-content text-start">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="text-muted fs-6 mb-0">Ticket Médio</p>
                                <div class="icone rounded border p-1">
                                    <i class="bi bi-align-middle fs-5"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold"><?= real_format($ticket_medio_vendas); ?></h5>
                            <small class="text-success"><?= $variacao_ticket_medio_span; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Receita -->
            <div class="col-md-6">
                <div class="metric-box p-2 mb-3  bg-body-tertiary border rounded shadow-sm">
                    <div class="metric-content text-start">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="text-muted fs-6 mb-0">Cancelada</p>
                                <div class="icone rounded border p-1">
                                    <i class="bi bi-file-x fs-5"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold"><?= real_format($valor_vendido_cancelado); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-box p-2 mb-3  bg-body-tertiary border rounded shadow-sm">
                    <div class="metric-content text-start">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="text-muted fs-6 mb-0">Pendente Para o Recebimento</p>
                                <div class="icone rounded border p-1">
                                    <i class="bi bi-arrow-repeat fs-5"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold"><?= real_format($valor_pedente_recebimento); ?></h5>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md">
                <div class="metric-box p-2 mb-3 bg-body-tertiary border rounded ">
                    <div class="d-flex justify-content-between align-items-center ">
                        <p class="text-muted fs-6 mb-0">Produtos Mais Vendidos</p>
                        <!-- Ícone de exportação -->
                        <button class="btn btn-sm btn-light border" onclick="exportTableToCSV('Produtos_mais_vendidos.csv','.produtos_mais_vendidos')">
                            <span class="text-dark">Exportar</span>
                            <i class="bi bi-filetype-csv fs-5"></i>
                        </button>
                    </div>
                    <div class="tabela">
                        <table class="table produtos_mais_vendidos table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade Vendida</th>
                                    <th>Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if ($consulta_produtos_mais_vendidos) {
                                    foreach ($consulta_produtos_mais_vendidos as $linha) {
                                        $qtd = $linha['qtd'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        $valor_vendido = ($linha['valorvendido']);
                                ?>
                                        <tr>
                                            <td><?= $descricao; ?></td>
                                            <td><?= $qtd; ?></td>
                                            <td><?= real_format($valor_vendido); ?></td>
                                        </tr>
                                <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Coluna direita com gráfico de Projeções vs Realidade (com moeda em real) -->
    <div class="col-md-7">
        <div class="metric-box p-2 mb-3  bg-body-tertiary border rounded">
            <p class="text-muted fs-6 mb-0">Período Atual vs Período Anterior</p>
            <canvas id="myChart-1"></canvas>
        </div>
    </div>
</div>
<!-- Adicionando tabelas de produtos mais vendidos e vendedores que mais venderam -->
<div class="row">
    <!-- Tabela de Produtos Mais Vendidos -->


    <!-- Tabela de Vendedores que Mais Realizaram Vendas -->
    <div class="col-md-5">
        <div class="metric-box p-2 mb-3 bg-body-tertiary border rounded ">
            <div class="d-flex justify-content-between align-items-center ">
                <p class="text-muted fs-6 mb-0">Vendedores com mais vendas</p>
                <!-- Ícone de exportação -->
                <button class="btn btn-sm btn-light border" onclick="exportTableToCSV('Vendedores_com_mais_vendas.csv','.vendedores_mais_vendas')">
                    <span class="text-dark">Exportar</span>
                    <i class="bi bi-filetype-csv fs-5"></i>
                </button>
            </div>
            <div class="tabela">
                <table class="table vendedores_mais_vendas table-striped">
                    <thead>
                        <tr>
                            <th>Vendedor</th>
                            <th>Quantidade Vendas</th>
                            <th>Valor Total</th>
                            <th>Comisão</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if ($consulta_vendedor_mais_vendas) {
                            foreach ($consulta_vendedor_mais_vendas as $linha) {
                                $vendedor = utf8_encode($linha['cl_nome']);
                                $total_vendas = utf8_encode($linha['totalvendas']);
                                $valor_vendido = ($linha['valorvendido']);
                                $comissao_percentual  = ($linha['cl_valor_comissao']);
                                // Cálculo da comissão
                                $comissao_prevista = $valor_vendido * $comissao_percentual;

                        ?>
                                <tr>
                                    <td><?= $vendedor; ?></td>
                                    <td><?= $total_vendas; ?></td>
                                    <td><?= real_format($valor_vendido); ?></td>
                                    <td><?= real_format($comissao_prevista); ?></td>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-box p-2 mb-3 bg-body-tertiary border rounded ">
            <div class="d-flex justify-content-between align-items-center ">
                <p class="text-muted fs-6 mb-0">Notas emitidas</p>
                <!-- Ícone de exportação -->
                <button class="btn btn-sm btn-light border" onclick="exportTableToCSV('Vendedores_com_mais_vendas.csv','.vendedores_mais_vendas')">
                    <span class="text-dark">Exportar</span>
                    <i class="bi bi-filetype-csv fs-5"></i>
                </button>
            </div>
            <div class="tabela">
                <table class="table vendedores_mais_vendas table-striped">
                    <thead>
                        <tr>
                            <th>Série</th>
                            <th>Quantidade</th>
                            <th>Valor Vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if ($consulta_notas_emitidas) {
                            $operacao_span = '';
                            foreach ($consulta_notas_emitidas as $linha) {
                                $serie_nf = ($linha['cl_serie_nf']);
                                $qtd = ($linha['qtd']);
                                $operacao = ($linha['cl_operacao']);
                                if ($operacao == "VENDA") {
                                    $operacao_span = "Venda";
                                } elseif ($operacao == "DEVCOMPRA") {
                                    $operacao_span = "Devolução de Compra";
                                } elseif ($operacao == "ESTORNOVENDA") {
                                    $operacao_span = "Estorno";
                                } elseif ($operacao == "SERVICO") {
                                    $operacao_span = "Serviço";
                                }

                        ?>
                                <tr>
                                    <td><?= $serie_nf; ?></td>
                                    <td><?= $qtd; ?></td>
                                    <td><?= $operacao_span; ?></td>

                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-box p-2 mb-3 bg-body-tertiary border rounded">
            <p class="text-muted fs-6 mb-0">Vendas por Forma de Pagamento</h5>
            <canvas id="myChart-2"></canvas>
        </div>
    </div>
</div>


<!-- Script para carregar o gráfico com valores em real -->
<script>
    var vendas_presente = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            $valor = consultar_vendas_anual_detalhado($ano_atual, $i)['valor'];
            echo       "'" . $valor . "',";
        }
        ?>
    ];
    var vendas_passado = [
        <?php
        $i = 0;
        while ($i <= 11) {
            $i = $i + 1;
            $valor = consultar_vendas_anual_detalhado($ano_passado, $i)['valor'];
            echo       "'" . $valor . "',";
        }
        ?>
    ];


    var label_forma_pagamento_venda = [
        <?php
        if ($valor_vendas_forma_pagamento) {
            foreach ($valor_vendas_forma_pagamento as $linha) {
                $formapagamento = utf8_encode($linha['formapagamento']);
                echo       "'" . $formapagamento . "',";
            }
        }
        ?>
    ];

    var valor_vendas_forma_pagamento = [
        <?php
        if ($valor_vendas_forma_pagamento) {
            foreach ($valor_vendas_forma_pagamento as $linha) {
                $valor = ($linha['valor']);
                echo       "'" . $valor . "',";
            }
        }
        ?>
    ];


</script>

<script src="js/venda/relatorio_atividades/containers/vendas.js"></script>