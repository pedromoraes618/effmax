<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="title mb-2">
    <label class="form-label sub-title ">Peças Aplicadas</label>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Referência</th>
            <th scope="col">Localização</th>
            <th scope="col">Qtd Orç</th>
            <th scope="col">Qtd Req</th>
            <th scope="col">Estoque</th>
            <th scope="col">Vlr Unit</th>
            <th scope="col">Vlr Total</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $total = 0;
        while ($linha = mysqli_fetch_assoc($consulta_material)) {
            $id = $linha['cl_id'];
            $produto_id = $linha['cl_produto_id'];
            $descricao = utf8_encode($linha['cl_item_descricao']);
            $referencia = $linha['cl_referencia'];
            $localizacao = $linha['localizacao'];
            $estoque = $linha['estoque'];

            $qtd_orcada = $linha['cl_quantidade_orcada'];
            $qtd_requisitada = $linha['cl_quantidade_requisitada'];
            $unidade = utf8_encode($linha['cl_unidade']);
            $valor_total = $linha['cl_valor_total'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $total += $valor_total;
        ?>
            <tr <?php if ($qtd_requisitada == $qtd_orcada) {
                    echo "class='table-success' title='Material já requisitado ao estoque'";
                } ?>>
                <th scope="row"><?= $produto_id ?></th>
                <td><?php echo $descricao; ?><br></td>
                <td> <?php echo $referencia ?></td>
                <td><?= $localizacao; ?></td>
                <td><?= $qtd_orcada ?></td>
                <td><?= $qtd_requisitada ?></td>
                <td><?= $estoque ?></td>
                <td><?= formatDecimal($valor_unitario); ?></td>
                <td><?= formatDecimal($valor_total); ?></td>
            </tr>
        <?php } ?>

    </tbody>
    <tfoot>
        <th scope="col" colspan="8">Total <?= $qtd; ?> </th>
        <th scope="col"><?php echo real_format($total); ?></th>
     
    </tfoot>
</table>

<!-- <script src="js/servico/ordem_servico/table/edita_pecas_ordem_servico.js"> -->