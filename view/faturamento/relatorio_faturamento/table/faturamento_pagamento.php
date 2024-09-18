<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/faturamento/relatorio_faturamento/gerenciar_faturamento.php";


?>

<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Faturamento Por Pagamento</h6>
    </div>
    <div class="card-body table-responsive">
        <?php
        if ($qtd_consulta > 0) {
        ?>
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th scope="col">Ranking</th>
                        <th scope="col">Forma pagamento</th>
                        <th scope="col">Valor</th>


                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ranking = 0;
                    $total = 0;
                    while ($linha = mysqli_fetch_assoc($consulta)) {
                        $ranking += 1;
                        $pagamento = utf8_encode($linha['pagamento']);
                        $valor = $linha['valor'];
                        $total += $valor;
                    ?>
                        <tr>

                            <td><?php echo ($ranking); ?></td>
                            <td><?php echo ($pagamento); ?></th>
                            <td><?php echo  real_format($valor); ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <th scope="col" colspan="2">Total</th>
                        <th scope="col"><?php echo real_format($total); ?></th>
                   
                    </tr>
                </tfoot>

            </table>
        <?php } else {
            echo '<div class="sem_registro"><img  class="img-fluid img_sem_registro" src="img/financeiro.svg"> </div>';
        } ?>
    </div>
</div>

<div class="card m-1  card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Detalhado Por Pagamento</h6>
    </div>
    <div class="card-body table-responsive">
        <canvas id="detalhado_por_pagamento" height="100"></canvas>

    </div>
</div>
<script>
    var media_faturamento_dash_pagamento = [
        <?php foreach ($dados_dashboard as $dados) : ?> '<?php echo ($media_valores_dashboard); ?>',
        <?php endforeach; ?>
    ];

    var label_faturamento_dash_pagamento = [
        <?php foreach ($dados_dashboard as $dados) : ?> '<?php echo ($dados['label']); ?>',
        <?php endforeach; ?>
    ];
    var valor_faturamento_dash_pagamento = [
        <?php foreach ($dados_dashboard as $dados) : ?> <?php echo $dados['valor']; ?>,
        <?php endforeach; ?>
    ];
</script>
<script src="js/faturamento/relatorio_faturamento/table/faturamento_pagamento.js"></script>