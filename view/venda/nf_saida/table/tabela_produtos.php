<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/venda_mercadoria/gerenciar_venda.php";
?>
<?php if ($qtd_consultar_produtos > 0) { ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Item</th>
                <th scope="col">Cód</th>
                <th scope="col">Descrição</th>
                <th scope="col">Und</th>
                <th scope="col">Vlr Unit</th>
                <th scope="col">Qtd</th>
                <th scope="col">Total</th>
                <th scope="col">Ncm</th>
                <th scope="col">Cst</th>
                <th scope="col">Cfop</th>
                <th scope="col">Aliq icms</th>
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
                $codigo_nf = $linha['cl_codigo_nf'];
                $produto_id = $linha['cl_item_id'];
                $descricao = utf8_encode($linha['cl_descricao_item']);
                $unidade = utf8_encode($linha['cl_unidade']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $quantidade = $linha['cl_quantidade'];
                $valor_unitario = $linha['cl_valor_unitario'];
                $Valor_total = $linha['cl_valor_total'];
                $ncm = $linha['cl_ncm'];
                $cfop = $linha['cl_cfop'];
                $cst = $linha['cl_cst'];
                $aliq_icms = $linha['cl_aliq_icms'];

                $somatorio_total = $Valor_total + $somatorio_total;

            ?>
                <tr>
                    <td><?php echo $item; ?></td>
                    <td><?php echo $produto_id; ?></td>
                    <td><?php echo ($descricao); ?></td>
                    <td><?php echo $unidade; ?></td>
                    <td><?php echo real_format($valor_unitario); ?></td>
                    <td><?php echo ($quantidade); ?></td>
                    <td><?php echo real_format($Valor_total); ?></td>
                    <td><?php echo ($ncm); ?></td>
                    <td><?php echo ($cst); ?></td>
                    <td><?php echo ($cfop); ?></td>
                    <td><?php echo formatarPorcentagem($aliq_icms); ?></td>
                    <?php if ($obs != "preview") { ?>
                        <td class="td-btn">
                            <div class="btn-group">
                                <button type="button" id_item_nf='<?php echo $id; ?>' codigo_nf='<?php echo $codigo_nf; ?>' title='Editar item' class="btn btn-sm btn-info editar_item_nf">Editar</button>
                                <button type="button" id_item_nf='<?php echo $id; ?>' codigo_nf='<?php echo $codigo_nf; ?>' title='Remover item' class="btn btn-sm btn-danger remover_item_nf"><i style="font-size: 1.4em;" class="bi bi-trash"></i></button>

                            </div>

                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan="6">Total</td>

                <th id="valor_total_produtos" scope="row"><?php echo real_format($somatorio_total); ?></th>
                <input type="hidden" id="vlr_total_prod" value="<?php echo $somatorio_total; ?>">


            </tr>
        </tfoot>
    </table>
<?php } else {
    include "../../../../view/alerta/alerta_delivery.php"; // mesnsagem para usuario pesquisar

} ?>
<script src="js/venda/nf_saida/table/editar_item.js">