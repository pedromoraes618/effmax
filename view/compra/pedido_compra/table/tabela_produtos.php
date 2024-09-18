<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/compra/pedido_compra/gerenciar_pedido.php";
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Und</th>
            <th scope="col">V.Unit</th>
            <th scope="col">Qtd</th>
            <th scope="col">V.Total</th>
            <th scope="col">Prazo</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody id="tabela_produtos">
        <?php
        $total_produtos = 0;
        $item = 0;
        while ($linha = mysqli_fetch_assoc($consultar_produtos)) {
            $item = $item + 1;
            $id = $linha['itemid'];
            $item_id = $linha['cl_item_id'];
            $descricao = utf8_encode($linha['cl_descricao_item']);
            $unidade = utf8_encode($linha['unidade']);
            $referencia = utf8_encode($linha['referencia']);

            $referencia_span = !empty($referencia) ? "<br><span>Ref: $referencia</span>" : '';

            $quantidade = $linha['cl_quantidade'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $Valor_total = $linha['cl_valor_total'];
            $prazo_entrega = $linha['cl_prazo_entrega'];
            $total_produtos += $Valor_total;
        ?>
            <tr>
                <td><?= $item; ?></td>
                <td><?= $item_id; ?></td>
                <td><?= $descricao . $referencia_span; ?></td>
                <td><?= $unidade; ?></td>
                <td><?= real_format($valor_unitario); ?></td>
                <td><?= ($quantidade); ?></td>
                <td><?= real_format($Valor_total); ?></td>
                <td><?= ($prazo_entrega . " dia(s)"); ?></td>
                <td>
                    <div class="btn-group">
                        <button type="button" data-item-id='<?= $id; ?>' title='Editar item' class="btn btn-sm btn-info alterar_item_pedido">Editar</button>
                        <button type="button" title='Remover item' data-item-id='<?= $id; ?>' class="btn btn-sm btn-danger remover_produto">
                            <i style="font-size: 1.4em;" class="bi bi-trash"> </i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="6">Sub total</th>
            <th><?= real_format($total_produtos); ?></th>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>


<script src="js/compra/pedido_compra/table/tabela_produtos.js">