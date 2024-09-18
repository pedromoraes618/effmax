<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/venda_mercadoria_delivery/gerenciar_venda.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Dt Venda</th>
                <th scope="col">Doc</th>
                <th scope="col">Cliente</th>
                <th scope="col">Vendedor</th>
                <th scope="col">Status</th>
                <th scope="col">Forma Pgt</th>
                <th scope="col">Desconto</th>
                <th scope="col">V.Liquido</th>
                <th scope="col"></th>
            
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;

            while ($linha = mysqli_fetch_assoc($consultar_venda_mercadoria)) {
                $id_b = ($linha['id']);
                $data_movimento_b = ($linha['cl_data_movimento']);
                $codigo_nf = ($linha['cl_codigo_nf']);
                $numero_nf_b = ($linha['cl_numero_nf']);
                $serie_nf_b = ($linha['cl_serie_nf']);
                $status_recebmento_b = ($linha['cl_status_recebimento']);
                $status_recebmento_b_2 = ($linha['cl_status_recebimento']);
                $forma_pagamento_b = utf8_encode($linha['formapgt']);
                $razao_social_b = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
                $valor_desconto_b = ($linha['cl_valor_desconto']);
                $valor_liquido_b = ($linha['cl_valor_liquido']);
                $vendedor_b = utf8_encode($linha['vendedor']);
                $tipo_pagamento = ($linha['tipopg']);
                $status_venda = ($linha['cl_status_venda']);
                $status_venda_id = ($linha['cl_status_venda']);
                $parceiro_avulso = ($linha['cl_parceiro_avulso']);


                if($status_venda_id=="1"){
                    $valor_total = $valor_liquido_b + $valor_total;
                }


                if ($parceiro_avulso != "") {
                    $cliente = $parceiro_avulso;
                } else {
                    $cliente =  $razao_social_b;
                }

                if ($tipo_pagamento != "3") {
                    $tipo_pagamento = "cartao";
                } else {
                    $tipo_pagamento = "faturado";
                }
            ?>
                <tr>
                    <th scope="row"><?php echo formatDateB($data_movimento_b) ?></th>
                    <td><?php echo $serie_nf_b . "" . $numero_nf_b; ?></td>
                    <td class="max_width_descricao"><?php echo $cliente;  ?></td>
                    <td><?php echo $vendedor_b; ?></td>
                    <td><span class='badge rounded-pill  text-bg-<?php if ($status_venda == "1") {
                                                                        echo 'warning';
                                                                        $status_venda = "Finalizado";
                                                                    } elseif ($status_venda == "2") {
                                                                        echo 'success';
                                                                        $status_venda = "Em andamento";
                                                                    } else {
                                                                        echo 'danger';
                                                                        $status_venda = "Cancelado";
                                                                    } ?>'><?php echo $status_venda; ?></td>

                    <td><span class="badge rounded-pill text-bg-primary"><?php echo ($forma_pagamento_b); ?></span></td>
                    <td><?php echo real_format($valor_desconto_b); ?></td>
                    <td><?php echo real_format($valor_liquido_b); ?></td>

                    <?php if ($status_recebmento_b == "1" and $status_venda_id != "3") {
                        echo  "<td class='td-btn'> <button type='button'  tipo_pagamento='$tipo_pagamento' 
                        venda_mercadoria_id='$id_b' class='btn btn-sm receber_nf'><i class='bi bi-clipboard-check-fill text-success fs-4'>
                        </i></button></td>";
                    } else {
                        echo "<td></td>";
                    }
                    ?>

                    <td class="td-btn">
                        <div class="btn-group">
                            <button type='button' pedido_id='<?php echo $id_b; ?>' serie_nf='VND' class='btn btn-sm  btn-warning comanda'>Comanda</button>
                            <button type="button" codigo_nf='<?php echo $codigo_nf; ?>' venda_mercadoria_id="<?php echo $id_b; ?>" class="btn btn-sm  btn-info editar_venda_mercadoria">Editar</button>
                        </div>
                    </td>

                </tr>

            <?php } ?>



        </tbody>
        <tfoot>
            <th scope="col">Total</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"><?php echo real_format($valor_total); ?></th>
            <th scope="col"></th>
            <th scope="col"></th>
 

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
<script src="js/venda/venda_mercadoria_delivery/table/editar_venda.js">