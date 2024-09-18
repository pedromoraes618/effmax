<?php include "../../../../modal/ecommerce/dashboard/gerenciar_dashboard.php"; ?>
<div class="col-md">
    <div class="row align-items-center g-4 mb-3 shadow">
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center">
                <i class="bi bi-bag-check fs-3 text-success"></i>
                <div class="ms-3">
                    <h5 class="mb-0"><?= $total_pedidos_aprovados; ?> Pedido(s)</h5>
                    <p class="form-text ">Pagamento aprovado</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><i class="bi bi-bag fs-3 text-info"></i>
                <div class="ms-3">
                    <h5 class="mb-0"><?= $total_pedidos_andamento; ?> Pedido(s)</h5>
                    <p class="form-text ">Aguardando pagamento</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex align-items-center"><i class="bi bi-bag-x text-danger fs-3"></i>
                <div class="ms-3">
                    <h5 class="mb-0"><?= $total_pedidos_cancelados; ?> Pedido(s)</h5>
                    <p class="form-text">Cancelado</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 ">
            <div class="card mb-2">
                <div class="card-header header-card-dashboard">
                    <h6><i class="bi bi-exclamation-octagon"></i> Pedidos Por Forma de Pagamento</h6>
                </div>
                <div class="card-body">
                    <canvas id="myChart-2"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md">
    <div class="row">
        <div class="col-md-6 ">
            <div class="card mb-2">

                <div class="card-header header-card-dashboard d-flex justify-content-between">
                    <h6><i class="bi bi-exclamation-octagon"></i> Total de Pedidos</h6>
                    <h6><?= real_format($total_pedidos); ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="myChart-1"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header header-card-dashboard d-flex justify-content-between">
                    <h6><i class="bi bi-exclamation-octagon"></i> Pedidos Por Estado</h6>
                </div>

                <div class="card-body">
                    <canvas id="myChart-3"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header header-card-dashboard d-flex justify-content-between">
                    <h6><i class="bi bi-exclamation-octagon"></i> Cupons Utilizados</h6>
                </div>

                <div class="card-body">
                    <canvas id="myChart-4"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header header-card-dashboard d-flex justify-content-between">
                    <h6><i class="bi bi-exclamation-octagon"></i> Clientes Ativos</h6>
                </div>

                <div class="card-body">
                    <canvas id="myChart-5"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var chart1 = document.getElementById('myChart-1').getContext('2d'); //total pedidos
    var chart2 = document.getElementById('myChart-2').getContext('2d'); //resumo pedidos por forma de pagamento - valor
    var chart3 = document.getElementById('myChart-3').getContext('2d'); //total de pedido por estado - quantidade
    var chart4 = document.getElementById('myChart-4').getContext('2d'); //resumo pedidos por forma de pagamento
    var chart5 = document.getElementById('myChart-5').getContext('2d'); //resumo pedidos por forma de pagamento

    var myChart = new Chart(chart1, {
        type: 'bar',
        data: {
            labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
            datasets: [
                <?php foreach ($row_grupo_pd_pagamento as $linha) {
                    $status_pagamento =  $linha['cl_status_pagamento'];
                    if ($status_pagamento == "approved") {
                        $status_pagamento_label = "Aprovado";
                    } elseif ($status_pagamento == "pending") {
                        $status_pagamento_label = "Pendente";
                    } elseif ($status_pagamento == "pending") {
                        $status_pagamento_label = "Pendente";
                    } elseif ($status_pagamento == "rejected") {
                        $status_pagamento_label = "Rejeitado";
                    } elseif ($status_pagamento == "cancelled") {
                        $status_pagamento_label = "Cancelado";
                    } else {
                        $status_pagamento_label = "Andamento";
                    }
                ?> {
                        label: '<?= $status_pagamento_label; ?>',
                        data: [
                            <?php
                            $i = 0;
                            while ($i <= 11) {
                                $i = $i + 1;
                                $dados_status_pagamento_grupo_pagamento_mes = array('mes' => $i, 'ano' => date('Y'), 'cl_status_pagamento' => $status_pagamento);
                                $row_grupo_pd_pagamento_mes = (consultar_pd_pagamento($dados_status_pagamento_grupo_pagamento_mes));


                                foreach ($row_grupo_pd_pagamento_mes as $total) {
                                    echo  "'" . $total['totalpedido']  . "',";
                                }
                            }
                            ?>
                        ],
                        backgroundColor: '<?= sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>'
                    },
                <?php } ?>
            ]
        },
        options: {
            locale: 'br-BR',
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            elements: {
                line: {
                    tension: 0
                }
            },
            tooltips: {
                backgroundColor: 'rgba(255, 255, 255, 1)',
                bodyFontColor: 'rgba(0, 0, 0, 1)',
                titleFontColor: 'rgba(0, 0, 0, 1)',
                titleFontSize: 20,
                caretPadding: 10,
                xPadding: 5,
                yPadding: 15,
                caretSize: 10,
                titleFontStyle: 'bold',

            }
        }
    });



    var myChart = new Chart(chart2, {
        type: 'bar',

        data: {
            labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
            datasets: [
                <?php foreach ($row_dados_resumo_vlr_pagamento as $linha) {
                    $forma_pgt_descricao = utf8_encode($linha['formapagamento']);
                    $pgt_id = $linha['pagamentoid'];
                ?> {
                        label: '<?= $forma_pgt_descricao; ?>',
                        type: 'line',
                        fill: false,
                        tension: 0.1,
                        data: [
                            <?php
                            $i = 0;
                            while ($i <= 11) {
                                $i = $i + 1;
                                $dados_resumo_vlr_pagamento_unico = array('mes' => $i, 'ano' => date('Y'), 'cl_pagamento_id_interno' => $pgt_id);
                                $row_dados_resumo_vlr_pagamento_unico = (consultar_resumo_vlr_pagamento($dados_resumo_vlr_pagamento_unico));

                                foreach ($row_dados_resumo_vlr_pagamento_unico as $linhatotal) {
                                    $total = $linhatotal['total'];
                                    echo  "'" . $total . "',";
                                }
                            }
                            ?>
                        ],
                        backgroundColor: '<?= sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>'
                    },
                <?php } ?>
            ]
        },
        options: {
            locale: 'br-BR',

        }
    });

    var myChart = new Chart(chart3, {
        type: 'doughnut',
        data: {
            labels: [
                <?php
                foreach ($pedidos_uf_array as $linha) {
                    $forma_pgt_descricao = utf8_encode($linha['cl_estado']);
                    echo  "'" . $forma_pgt_descricao . "',";
                } ?>
            ],
            datasets: [
                <?php
                ?> {
                    label: 'Total',
                    data: [
                        <?php foreach ($pedidos_uf_array as $linha) {
                            $total = $linha['total'];
                            echo  "'" . $total . "',";
                        } ?>
                    ],
                    backgroundColor: '<?= sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>',

                },

            ]
        },
        options: {
            locale: 'br-BR',
            tooltips: {
                backgroundColor: 'rgba(255, 255, 255, 1)',
                bodyFontColor: 'rgba(0, 0, 0, 1)',
                titleFontColor: 'rgba(0, 0, 0, 1)',
                titleFontSize: 20,
                caretPadding: 10,
                xPadding: 5,
                yPadding: 15,
                caretSize: 10,
                titleFontStyle: 'bold',
            },

        }
    });

    var myChart = new Chart(chart4, {
        type: 'polarArea',
        data: {
            labels: [
                <?php
                foreach ($pedidos_cupom_array as $linha) {
                    $cupom = utf8_encode($linha['cl_cupom']);
                    echo  "'" . $cupom . "',";
                } ?>
            ],
            datasets: [
                <?php
                ?> {
                    label: 'Total',
                    data: [
                        <?php foreach ($pedidos_cupom_array as $linha) {
                            $total = $linha['total'];
                            echo  "'" . $total . "',";
                        } ?>
                    ],
                    backgroundColor: '<?= sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>',
                    radius: '80%'
                },

            ]
        },
        options: {
            locale: 'br-BR',
            tooltips: {
                backgroundColor: 'rgba(255, 255, 255, 1)',
                bodyFontColor: 'rgba(0, 0, 0, 1)',
                titleFontColor: 'rgba(0, 0, 0, 1)',
                titleFontSize: 20,
                caretPadding: 10,
                xPadding: 5,
                yPadding: 15,
                caretSize: 10,
                titleFontStyle: 'bold',
            },

        }
    });



    var myChart = new Chart(chart5, {
        type: 'bar',
        data: {
            labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
            datasets: [
                <?php
                ?> {
                    label: 'Total',
                    data: [
                        <?php foreach ($cliente_array as $linha) {
                            $total = $linha['total'];
                            echo  "'" . $total . "',";
                        } ?>
                    ],
                    backgroundColor: '<?= sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>',

                },

            ]
        },
        options: {
            locale: 'br-BR',
            tooltips: {
                backgroundColor: 'rgba(255, 255, 255, 1)',
                bodyFontColor: 'rgba(0, 0, 0, 1)',
                titleFontColor: 'rgba(0, 0, 0, 1)',
                titleFontSize: 20,
                caretPadding: 10,
                xPadding: 5,
                yPadding: 15,
                caretSize: 10,
                titleFontStyle: 'bold',
            },

        }
    });
</script>
<script src="js/ecommerce/dashboard/containers/resumo_pedido_1.js"></script>