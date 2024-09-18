<?php
include "../../../../modal/servico/lista_servico/gerenciar_lista_servico.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>

                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Valor</th>
                <th scope="col">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar_servico)) {
                $id = $linha['cl_id'];
                $descricao = utf8_encode($linha['cl_descricao']);
                $valor = ($linha['cl_valor']);
                $status = ($linha['cl_status']);
                if ($status == 1) { //os fechada
                    $status = "<span class='badge rounded-pill text-bg-success'>Ativo</span></td>";
                } else {
                    $status = "<span class='badge rounded-pill text-bg-danger'>Inativo</span></td>";
                }
            ?>
                <tr>
                    <th><?php echo ($id); ?></th>
                    <td><?php echo $descricao; ?></td>
                    <td><?php echo real_format($valor); ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <button type="buttom" id='<?php echo $id; ?>' valor_unitario=<?php echo $valor; ?> class="btn btn-sm btn-info selecionar_servico">Selecionar</button>
                    </td>
                    <td style="display: none;">
                        <input type="hidden" value="<?php echo $descricao; ?>" name="" id="descricao_<?php echo $id; ?>">
                    </td>
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
<script src="js/include/servico/pesquisa_servico.js"></script>