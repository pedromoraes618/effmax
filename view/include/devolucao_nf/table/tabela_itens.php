<?php
include "../../../../modal/devolucao_nf/devolucao_mercadoria/gerenciar_devolucao.php";
?>
<div class="title mb-2">
    <label class="form-label sub-title">Itens</label>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Und</th>
            <th scope="col">Vlr Unit</th>
            <th scope="col">Qtd</th>
            <th scope="col">Total</th>
            <th scope="col">Qtd Dev</th>
        </tr>
    </thead>

    <tbody id="tabela_produtos">
        <?php
        $somatorio_total = 0;
        $item = 0;
        if ($tipo != "entradadev") {//venda
            while ($linha = mysqli_fetch_assoc($consultar_nf_itens)) {
                $item = $item + 1;
                $id = $linha['cl_id'];
                $item_id = $linha['cl_item_id'];
                $descricao = utf8_encode($linha['cl_descricao_item']);
                $unidade = utf8_encode($linha['cl_unidade']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $quantidade = $linha['cl_quantidade'];
                $valor_unitario = $linha['cl_valor_unitario'];
                $Valor_total = $linha['cl_valor_total'];
                $somatorio_total = $Valor_total + $somatorio_total;

        ?>
                <tr>

                    <td><?php echo $item; ?></td>
                    <td><?php echo $item_id; ?></td>
                    <td><?php echo ($descricao); ?></td>
                    <td><?php echo $unidade; ?></td>
                    <td><?php echo formatDecimal($valor_unitario); ?></td>
                    <td><?php echo ($quantidade); ?></td>
                    <td><?php echo formatDecimal($Valor_total); ?></td>

                    <td style="width:100px">
                        <input type="text" class="form-control" name="<?php echo $id . "dev"; ?>"></div>
                    </td>
                </tr>
            <?php
            }
        } else {//compra
            while ($linha = mysqli_fetch_assoc($consultar_nf_itens)) {
                $item = $item + 1;
                $id = $linha['cl_id'];
                $item_id = $linha['cl_produto_id'];
                $descricao = utf8_encode($linha['cl_descricao']);
                $unidade = utf8_encode($linha['cl_und']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $quantidade = $linha['cl_quantidade'];
                $valor_unitario = $linha['cl_valor_unitario'];
                $Valor_total = $linha['cl_valor_total'];
                $somatorio_total = $Valor_total + $somatorio_total;

            ?>
                <tr>

                    <td><?php echo $item; ?></td>
                    <td><?php echo $item_id; ?></td>
                    <td><?php echo ($descricao); ?></td>
                    <td><?php echo $unidade; ?></td>
                    <td><?php echo formatDecimal($valor_unitario); ?></td>
                    <td><?php echo ($quantidade); ?></td>
                    <td><?php echo formatDecimal($Valor_total); ?></td>

                    <td style="width:100px">
                        <input type="text" class="form-control" name="<?php echo $id . "dev"; ?>"></div>
                    </td>
                </tr>
        <?php

            }
        } ?>
    </tbody>


    <tfoot>
        <tr>
            <th scope="row" colspan="6">Total</td>
            <th scope="row"><?php echo real_format($somatorio_total); ?></th>
            <th></th>
        </tr>
    </tfoot>
</table>

<script src="js/venda/venda_mercadoria/table/tabela_produtos.js">