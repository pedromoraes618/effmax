<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/venda_mercadoria/gerenciar_venda.php";
?>
<div class="title mb-2">
    <label class="form-label sub-title">Itens</label>
</div>
<?php if ($qtd_consultar_produtos > 0) { ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Und</th>
                <th scope="col">Vlr Unit</th>
                <th scope="col">Qtd</th>
                <th scope="col">Total</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="tabela_produtos">
            <?php
            $somatorio_total = 0;
            $item = 0;
            while ($linha = mysqli_fetch_assoc($consultar_produtos)) {
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
                    <td><?php echo $item_id; ?></td>
                    <td><?php echo ($descricao); ?></td>
                    <td><?php echo $unidade; ?></td>
                    <td><?php echo formatDecimal($valor_unitario); ?></td>
                    <td><?php echo ($quantidade); ?></td>
                    <td><?php echo formatDecimal($Valor_total); ?></td>
                    <td class="td-btn">
                        <div class="btn-group">
                            <button type="button" quantidade_prod='<?php echo $quantidade; ?>' id_produto='<?php echo $item_id; ?>' id_item_nf='<?php echo $id; ?>' title='Remover item' class="btn btn-sm btn-danger remover_produto"><i style="font-size: 1.4em;" class="bi bi-trash"></i></button>
                            <button type="button" produto_id='<?php echo $id; ?>' title='Editar item' class="btn btn-sm btn-info alterar_produto_vnd">Editar</button>
                        </div>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan="5">Total</td>
                <th id="valor_total_produtos" scope="row"><?php echo real_format($somatorio_total); ?></th>
                <input type="hidden" id="vlr_total_prod" value="<?php echo $somatorio_total; ?>">
                <th></th>
            </tr>
        </tfoot>
    </table>
<?php } else {
    include "../../../../view/alerta/alerta_delivery.php"; // mesnsagem para usuario pesquisar

} ?>
<script src="js/venda/venda_mercadoria/table/tabela_produtos.js">