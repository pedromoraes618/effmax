<?php
include "../../../../modal/estoque/produto/gerenciar_produto.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Subgrupo</th>
                <th scope="col">Estoque</th>
                <th scope="col">Prç custo</th>
                <th scope="col">Prç venda</th>

            </tr>
        </thead>
        <tbody id="produtos-tbody">
            <?php
            $item = 0;
            while ($linha = mysqli_fetch_assoc($consultar_produtos)) {

                $produto_id = $linha['produtoid'];
                $codigo_nf = $linha['cl_codigo'];
                $descricao = utf8_encode($linha['descricao']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $estoque_minimo = ($linha['cl_estoque_minimo']);
                $estoque_maximo = ($linha['cl_estoque_maximo']);
                $subgrupo = utf8_encode($linha['subgrupo']);
                $und = utf8_encode($linha['und']);
                $fabricante = utf8_encode($linha['cl_fabricante']);
                $estoque = $linha['cl_estoque'];
                $preco_venda = ($linha['cl_preco_venda']);
                $preco_custo = ($linha['cl_preco_custo']);

                $ativo = ($linha['ativo']);
                $preco_promocao = ($linha['cl_preco_promocao']);
                $data_validade_promocao = ($linha['cl_data_valida_promocao']);
                $data_ajst_preco_venda = ($linha['cl_data_ajst_preco_venda']);
                $span_promocao = null;
                if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //promoção
                    $span_promocao = "<span class='text-success'>Promoção " . real_format($preco_promocao) . " Até " . formatDateB($data_validade_promocao) . "</span>";;
                }
                $item++;


            ?>
                <tr>
                    <input type="hidden" name="produto_id_<?= $item; ?>" value="<?= $produto_id; ?>">

                    <th><input style="width: 20px; height: 20px;" name="check_<?= $item ?>" class="form-check-input" checked type="checkbox" value="<?= $produto_id; ?>>" id="flexCheckDefault"></th>

                    <th scope="row"><?= $produto_id ?></th>
                    <td><?= $descricao; ?>
                        <hr class="mb-1">Ref: <?= $referencia; ?>
                        <br class="m-0">Ult ajst venda: <?= formatDateB($data_ajst_preco_venda); ?><br><?= $span_promocao; ?>
                    </td>
                    <td><?= $subgrupo; ?></td>
                    <td><?= $estoque; ?></td>
                    <td><?= real_format($preco_custo) ?></td>
                    <td><?= real_format($preco_venda) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <label>
        Registros <?php echo $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<!-- <script src="js/estoque/ajuste_preco/table/consultar_produto_lote.js"> -->