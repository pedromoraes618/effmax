<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/cotacao_mercadoria/gerenciar_cotacao.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Dt. Cotação</th>
                <th scope="col">Doc</th>
                <th scope="col">Cliente</th>
                <th scope="col">Vendedor</th>
                <th scope="col">Status</th>
                <th scope="col">Válidade</th>
                <th scope="col">Desconto</th>
                <th scope="col">Vlr.Liquido</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;

            while ($linha = mysqli_fetch_assoc($consultar_cotacao_mercadoria)) {
                $id_cotacao = ($linha['idcotacao']);
                $codigo_nf = ($linha['cl_codigo_nf']);
                $serie_nf = ($linha['cl_serie_nf']);
                $numero_nf = ($linha['cl_numero_nf']);
                $data_movimento = ($linha['cl_data_movimento']);
                $razao_social = utf8_encode($linha['cl_razao_social']);
                $parceiro_avulso = utf8_encode($linha['cl_parceiro_avulso']);
                $vendedor = utf8_encode($linha['vendedor']);
                $status_cotacao_id = utf8_encode($linha['cl_status_cotacao_id']);
                $statusc = utf8_encode($linha['statusc']);
                $validade = ($linha['cl_validade']);
                $valor_desconto = ($linha['cl_valor_desconto']);
                $valor_liquido = ($linha['cl_valor_liquido']);
                $valor_total = $valor_liquido + $valor_total;

            ?>
                <tr>
                    <th scope="row"><?php echo formatDateB($data_movimento) ?></th>
                    <td><?= $serie_nf . $numero_nf; ?></td>
                    <td class="max_width_descricao"><?php echo $razao_social;  ?>
                        <?php if ($parceiro_avulso != '') {
                            echo "<hr class='mb-0'>$parceiro_avulso";
                        } ?>

                    </td>
                    <td><?php echo $vendedor; ?></td>
                    <td><span class='badge rounded-pill  text-bg-<?php if ($status_cotacao_id == "1") {
                                                                        echo 'primary';
                                                                    } elseif ($status_cotacao_id == "2" or $status_cotacao_id == "3") {
                                                                        echo 'success';
                                                                    } else {
                                                                        echo 'danger';
                                                                    } ?>'><?php echo $statusc; ?></td>

                    <td><?php echo ($validade);
                        if ($validade > 0) {
                            echo ' dias';
                        } ?> </td>
                    <td><?php echo real_format($valor_desconto); ?></td>
                    <td><?php echo real_format($valor_liquido); ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" data-codigo-nf='<?php echo $codigo_nf; ?>'
                                data-cotacao-id="<?php echo $id_cotacao; ?>" class="btn btn-sm  btn-info editar_cotacao">Editar</button>
                            <button type="button" title="Pdf da cotação" data-codigo-nf='<?php echo $codigo_nf; ?>'
                                class="btn btn-sm btn-dark  pdf_cotacacao"><i class="bi bi-file-earmark-pdf-fill"></i></button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="7" scope="col">Total</th>
            <th scope="col"><?php echo real_format($valor_total); ?></th>
            <th></th>
        </tfoot>
    </table>
    <label>
        Registros <?php echo $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/venda/cotacao_mercadoria/table/editar_cotacao.js">