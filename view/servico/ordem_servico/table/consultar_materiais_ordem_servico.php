<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="title mb-2">
    <label class="form-label sub-title">Registros de Materiais</label>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Ref</th>
            <th scope="col">Localização</th>
            <th scope="col">Qtd Orç</th>
            <th scope="col">Qtd Req</th>
            <th scope="col">Estoque</th>
            <?php if ($tipo_produto_id != "10") { ?>
                <th scope="col">Vlr Unit</th>
                <th scope="col">Vlr Total</th>
            <?php } ?>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php

        $total = 0;

        while ($linha = mysqli_fetch_assoc($consulta_material)) {
            $span_descservico = '';
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
            $descservico = utf8_encode($linha['descservico']);
            $servid = ($linha['servid']);

            if (!empty($descservico)) {
                $span_descservico = "<hr class='mb-1'> Serviço: $servid - $descservico";
            }
            $total += $valor_total;
        ?>
            <tr <?php if ($qtd_requisitada == $qtd_orcada) {
                    echo "class='table-success' title='Material já requisitado ao estoque'";
                } ?>>
                <th scope="row"><?= $produto_id ?></th>
                <td><?= $descricao . $span_descservico; ?><br></td>
                <td> <?php echo $referencia ?></td>
                <td><?= $localizacao; ?></td>
                <td><?= $qtd_orcada ?></td>
                <td><?= $qtd_requisitada ?></td>
                <td><?= $estoque ?></td>
                <?php if ($tipo_produto_id != "10") { ?>
                    <td><?= real_format($valor_unitario); ?></td>
                    <td><?= real_format($valor_total); ?></td>
                <?php } ?>
                <td class="td-btn">
                    <div class="btn-group">
                        <button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-info editar_peca">Editar</button>
                        <button type="button" id='<?php echo $id; ?>' title='Remover Material' class="btn btn-sm btn-danger remover_material"><i style="font-size: 1.4em;" class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
        <?php } ?>

    </tbody>
    <?php if ($tipo_produto_id != "10") { ?>
        <tfoot>
            <th scope="col" colspan="8">Total <?= $qtd; ?> </th>
            <th scope="col"><?php echo real_format($total); ?></th>
            <th></th>
        </tfoot>
    <?php } ?>
</table>

<script src="js/servico/ordem_servico/table/edita_pecas_ordem_servico.js">