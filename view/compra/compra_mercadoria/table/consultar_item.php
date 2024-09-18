<?php

include "../../../../modal/compra/compra_mercadoria/gerenciar_compra.php";

?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Item</th>
            <th scope="col">Cód</th>
            <th scope="col">Descrição</th>
            <th scope="col">Und</th>
            <th scope="col">V.Unit</th>
            <th scope="col">Qtd</th>
            <th scope="col">V.Total</th>
            <th scope="col">Cfop</th>
            <th scope="col">V.Custo</th>
            <th scope="col">V.Vnd Sug</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $item = 0;
        $total = 0;
        while ($linha = mysqli_fetch_assoc($consultar_compra_item)) {
            $item = $item + 1;
            $codigo_nf = $linha['cl_codigo_nf'];
            $id = $linha['cl_id'];
            $produto_id = $linha['cl_produto_id'];
            $descricao = utf8_encode($linha['cl_descricao']);
            $cfop = $linha['cl_cfop'];
            $und = $linha['cl_und'];
            $quantidade = $linha['cl_quantidade'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $valor_total = $quantidade * $valor_unitario;
            $aliq_icms = $linha['cl_aliq_icms'];
            $custo = calcular_custo_item_nf($id);
            $valor_custo = ($custo['valor_custo']);
            $prc_vnd_sugerido = ($custo['prc_vnd_sugerido']);
            $total += $valor_total;
        ?>
            <tr <?php if ($produto_id == "") {
                    echo "class='table-danger' title='É necessario associar esse produto ao item do estoque'";
                } ?>>
                <th scope="row"><?php echo ($item); ?></th>
                <td><?php echo ($produto_id); ?></td>
                <td><?php echo ($descricao); ?></td>
                <td><?php echo ($und); ?></td>
                <td><?php echo formatDecimal($valor_unitario); ?></td>
                <td><?php echo ($quantidade); ?></td>
                <td><?php echo formatDecimal($valor_total); ?></td>
                <td><?php echo ($cfop); ?></td>
                <td><?php echo formatDecimal($valor_custo) ?></td>
                <td><?php echo formatDecimal($prc_vnd_sugerido) ?></td>
                <td class="td-btn">
                    <div class="btn-group">
                        <button type="button" codigo_nf='<?php echo $codigo_nf; ?>' item_id=<?php echo $id; ?> class="btn btn-info   btn-sm editar_item_nf">Editar</button>
                        <button type="button" codigo_nf='<?php echo $codigo_nf; ?>' item_id='<?php echo $id; ?>' title='Remover item' class="btn btn-sm btn-danger remover_item"><i style="font-size: 1.4em;" class="bi bi-trash"></i></button>
                </td>
            </tr>


        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">Total</th>
            <th><?php echo  real_format($total); ?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

<script src="js/compra/compra_mercadoria/table/editar_item.js"></script>