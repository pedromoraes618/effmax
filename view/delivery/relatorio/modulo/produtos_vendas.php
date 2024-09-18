<?php
include "../../../../modal/delivery/relatorio/gerenciar_relatorio.php";
?>
<div class="shadow p-2">
    <div class="row m-1 mb-3">
        <div class="col-md-12 p-0 m-1 border-0">
            <div class="title">
                <label class="form-label">Produtos Mais Vendidos</label>
            </div>
            <div class="card border-0 ">
                <div class="card-body">
                    <canvas id="myChart-2"></canvas>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    var descricao_produtos_vendidos = [
        <?php

        while ($linha = mysqli_fetch_assoc($consulta_produtos_vendas_descricao)) {
            $descricao_item = utf8_encode($linha['cl_descricao_item']);
            $valor = real_format($linha['valortotal']);
            echo "'" . $descricao_item ." - ".$valor . "',";
        }
        ?>
    ];
    var qtd_produtos_vendidos = [
        <?php

        while ($linha = mysqli_fetch_assoc($consulta_produtos_vendas)) {
            $quantidade = $linha['total_vendido'];
            echo "'" . $quantidade . "',";
        }
        ?>
    ];
</script>
<script src="js/delivery/relatorio/modulo/produtos_vendas.js"></script>