<?php
include "../../../../modal/delivery/relatorio/gerenciar_relatorio.php";
?>
<div class="shadow p-2">
    <div class="row m-1 mb-3">
        <div class="col-md-12 p-0 m-1 border-0">
            <div class="title">
                <label class="form-label">Vendas</label>
            </div>
            <div class="card border-0 ">
                <div class="card-body">
                    <canvas id="myChart-1"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <h6 class="border border-1 rounded p-1"><i class="bi bi-exclamation-circle-fill"></i> Resumo</h6>

            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                            <i class="bi bi-cart-check-fill"></i> Vendas
                        </div>
                        <div class="col">
                            <?php echo real_format($valor_total_vendas)  ?>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                        <i class="bi bi-stop-fill"></i>  Consumo no local
                        </div>
                        <div class="col">
                            <?php echo real_format($valor_opcao_delivery_vendas_local) . " - " . formatarPorcentagem(($qtd_opcao_delivery_vendas_local / $qtd_vendas) * 100) ?>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                        <i class="bi bi-stop-fill"></i>  Retirada
                        </div>
                        <div class="col">
                            <?php echo real_format($valor_opcao_delivery_vendas_retirada) . " - " . formatarPorcentagem(($qtd_opcao_delivery_vendas_retirada / $qtd_vendas) * 100) ?>

                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                        <i class="bi bi-stop-fill"></i>   Entrega
                        </div>
                        <div class="col">
                            <?php echo real_format($valor_opcao_delivery_vendas_entrega) . " - " . formatarPorcentagem(($qtd_opcao_delivery_vendas_entrega / $qtd_vendas) * 100) ?>

                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">
                        <i class="bi bi-stop-fill"></i>      Cancelamento
                        </div>
                        <div class="col">
                            <?php echo real_format($valor_delivery_vendas_canceladas) . " - " . formatarPorcentagem(($qtd_delivery_vendas_canceladas / $qtd_vendas) * 100)  ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-6 mb-2">
            <h6 class="border border-1 rounded p-1"><i class="bi bi-exclamation-circle-fill"></i> Por Forma de Pagamento</h6>
            <ul class="list-group">

                <?php while ($linha = mysqli_fetch_assoc($consulta_vendas_fpg)) {
                    $descricao = utf8_encode($linha['formapagamento']);
                    $valortotal = ($linha['valortotal']);
                ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col">
                                <i class="bi bi-stop-fill"></i><?php echo $descricao; ?>
                            </div>
                            <div class="col">
                                <?php echo  real_format($valortotal); ?>
                            </div>
                        </div>
                    </li>
                <?php
                } ?>

            </ul>
        </div>
    </div>
</div>

<script>
    var valor_relatorio_vendas_dash = [
        <?php
        $i = 0;
        while ($i <= 3) {

            echo       "'" . $array_relatorio_vendas[$i] . "',";
            $i = $i + 1;
        }
        ?>
    ];
</script>
<script src="js/delivery/relatorio/modulo/vendas.js"></script>