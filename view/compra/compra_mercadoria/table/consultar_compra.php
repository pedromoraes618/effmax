<?php
include "../../../../modal/compra/compra_mercadoria/gerenciar_compra.php";
?>

<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Dt Entrada</th>
                <th scope="col">Dt Emissão</th>
                <th scope="col">Doc</th>
                <th scope="col">Fornecedor</th>
                <th scope="col">Forma Pgt</th>
                <th scope="col">Vlr.liquido</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = null;
            while ($linha = mysqli_fetch_assoc($consultar_compra)) {
                $id = $linha['idnota'];
                $codigo_nf = $linha['cl_codigo_nf'];
                $data_entrada = ($linha['cl_data_entrada']);
                $data_emissao = ($linha['cl_data_emissao']);
                $serie_nf = ($linha['cl_serie_nf']);
                $numero_nf = ($linha['cl_numero_nf']);
                $fornecedor = utf8_encode($linha['fornecedor']);
                $formapgt = utf8_encode($linha['formapgt']);
                $valor_total_nota = ($linha['cl_valor_total_nota']);
                $status_nf = ($linha['cl_status_nf']);
                $status_provisionamento = ($linha['cl_status_provisionamento']);
                if ($status_nf == 1 or $status_nf == 2) {
                    $valor_total += $valor_total_nota;
                }
                if ($status_nf == "1") {
                    $status = "<span class='badge rounded-pill text-bg-success'>Concluida</span>";
                } elseif ($status_nf == "2") {
                    $status = "<span class='badge rounded-pill text-bg-primary'>Em andamento</span>";
                } elseif ($status_nf == "3") {
                    $status = "<span class='badge rounded-pill text-bg-danger'>Cancelada</span>";
                }

            ?>
                <tr>

                    <th scope="row"><?php echo formatDateB($data_entrada); ?></th>
                    <td><?php echo formatDateB($data_emissao); ?></td>
                    <td><?php echo $serie_nf . "" . $numero_nf; ?></td>
                    <td><?php echo $fornecedor; ?></td>
                    <td><?php echo $formapgt; ?></td>
                    <td><?php echo real_format($valor_total_nota); ?></td>
                    <td><?php echo ($status); ?></td>
                    <?php if ($status_provisionamento == "1" and $status_nf == "1") {
                        echo  "<td class='td-btn'> <button type='button' title='Pendente para Provisionamento' tipo_pagamento='faturado' 
           nf_entrada_id='$id' class='btn btn-sm provisionar_nf_entrada'><i class='bi bi-clipboard-check-fill text-dark  fs-4'>
           </i></button></td>";
                    } else {
                        echo "<td></td>";
                    }
                    ?>
                    <td class="td-btn">
                        <div class="btn-group">
                            <button type="button" codigo_nf='<?php echo $codigo_nf; ?>' nf_entrada_id='<?php echo $id; ?>' class="btn btn-info   btn-sm editar_nf_entrada">Editar</button>
                            <div class="input-group ">
                                <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Ações</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item devolucao_nf_entrada" style="cursor: pointer;" nf_entrada_id='<?php echo $id; ?>'>Devolução</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td><?php echo real_format($valor_total); ?></td>
            </tr>
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
<script src="js/compra/compra_mercadoria/table/editar_compra.js"></script>