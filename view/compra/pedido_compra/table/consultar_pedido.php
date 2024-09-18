<?php include "../../../../modal/compra/pedido_compra/gerenciar_pedido.php"; ?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Data</th>
                <th scope="col">Doc</th>
                <th scope="col">Fornecedor/Cliente</th>
                <th scope="col">Operação</th>
                <th scope="col">Status</th>
                <th scope="col">Desconto</th>
                <th scope="col">Vlr.Liquido</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar_pedido_compra)) {
                $pedido_id = ($linha['idpedido']);
                $codigo_nf = ($linha['cl_codigo_nf']);
                $serie_nf = ($linha['cl_serie_nf']);
                $numero_nf = ($linha['cl_numero_nf']);
                $data_movimento = ($linha['cl_data_movimento']);
                $razao_social = utf8_encode($linha['cl_razao_social']);
                $status_pedido = utf8_encode($linha['statuspedido']);
                $status_id = utf8_encode($linha['cl_status_id']);
                $valor_desconto = ($linha['cl_valor_desconto']);
                $valor_liquido = ($linha['cl_valor_liquido']);
                $operacao = ($linha['cl_operacao']);
                $solicitacao = ($linha['cl_numero_solicitacao']);
                $data_aprovacao = ($linha['cl_data_aprovacao']);
                $span_operacao = '';
                $span_data_aprovacao = '';

                if ($operacao == "venda") {
                    $span_operacao = "<span class='badge rounded-pill text-bg-primary'>Venda</span>";
                } elseif ($operacao == "compra") {
                    $span_operacao = "<span class='badge rounded-pill text-bg-dark'>Compra</span>";
                }

                $span_data_aprovacao = formatDateB($data_aprovacao) != "" ? "<span>  Aprovado em " . formatDateB($data_aprovacao) . "</span>" : '';
                $valor_total = $valor_liquido + $valor_total;
            ?>
                <tr>
                    <th scope="row"><?= formatDateB($data_movimento) ?></th>
                    <td><?= $serie_nf . $numero_nf; ?><br><?php if (!empty($solicitacao)) {
                                                                echo "Sol: $solicitacao";
                                                            } ?></td>
                    <td class="max_width_descricao"><?= $razao_social;  ?></td>
                    <td><?= $span_operacao; ?></td>
                    <td><span class='badge rounded-pill text-bg-primary'><?= $status_pedido; ?></span>
                        <br><?= $span_data_aprovacao; ?>
                    </td>
                    <td><?= real_format($valor_desconto); ?></td>
                    <td><?= real_format($valor_liquido); ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" data-codigo_nf='<?= $codigo_nf; ?>'
                                data-pedido_id="<?= $pedido_id; ?>" class="btn btn-sm  btn-info editar_pedido">Editar</button>

                            <button type="button" title="Pdf do pedido" data-codigo_nf='<?= $codigo_nf; ?>'
                                data-pedido_id="<?= $pedido_id; ?>" class="btn btn-sm btn-dark pdf_pedido"><i class="bi bi-file-earmark-pdf-fill" style="font-size: 1.5em;"></i></button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="6" scope="col">Total</th>
            <th><?= real_format($valor_total); ?></th>
            <th></th>
        </tfoot>
    </table>
    <label>
        Registros <?= $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/compra/pedido_compra/table/editar_pedido.js">