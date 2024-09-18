<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/cotacao_mercadoria/gerenciar_cotacao.php";
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
            <th scope="col">Status</th>
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

            $id = $linha['cl_id'];
            $item_id = $linha['cl_item_id'];
            $descricao = utf8_encode($linha['cl_descricao_item']);
            $unidade = utf8_encode($linha['cl_unidade']);
            $referencia = utf8_encode($linha['cl_referencia']);
            $prazo_entrega = $linha['cl_prazo_entrega'];

            $quantidade = $linha['cl_quantidade'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $status_item = $linha['cl_status_item'];
            $Valor_total = $linha['cl_valor_total'];
            $total_produtos += $Valor_total;

            $referencia_span = !empty($referencia) ? "<hr class='mb-1'><span>Ref: $referencia</span>" : '';

            if ($status_item == 1) {
                $status = "<p>Aberto</p>";
            } elseif ($status_item == 2) {
                $status = "<p class='text-success'>Ganho</p>";
            } else {
                $status = "<p class='text-danger'>Perdido</p>";
            }
        ?>
            <tr>
                <td><?php echo $item; ?></td>
                <td><?php echo $item_id; ?></td>
                <td><?= $descricao . $referencia_span; ?></td>
                <td><?php echo $unidade; ?></td>
                <td><?php echo real_format($valor_unitario); ?></td>
                <td><?php echo ($quantidade); ?></td>
                <td><?php echo real_format($Valor_total); ?></td>
                <td><?php echo $status; ?></td>
                <td><?= ($prazo_entrega . " dia(s)"); ?></td>
                <td>
                    <div class="btn-group">
                        <button type="button" data-item-id='<?php echo $id; ?>' title='Editar item' class="btn btn-sm btn-info alterar_item_cotacao">Editar</button>
                        <button type="button" data-item-id='<?php echo $id; ?>' title='Remover item' class="btn btn-sm btn-danger remover_item"> <i style="font-size: 1.4em;" class="bi bi-trash"> </i></button>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <th colspan="6" scope="col">Total</th>
        <th><?= real_format($total_produtos); ?></th>
        <th></th>
    </tfoot>

</table>

<script src="js/venda/cotacao_mercadoria/table/tabela_produtos.js">