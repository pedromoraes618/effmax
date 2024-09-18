<?php

include "../../../../modal/estoque/ajuste_estoque1/gerenciar_ajuste_estoque.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>

                <th scope="col">Código</th>
                <th scope="col">Data</th>
                <th scope="col">Qtd ajuste</th>
                <th scope="col">Usuário</th>
                <th></th>

            </tr>
        </thead>
        <tbody id="produtos-tbody">
            <?php
            $item = 0;
            while ($linha = mysqli_fetch_assoc($consultar)) {
                $codigo_nf = $linha['cl_codigo_nf'];
                $documento = $linha['cl_documento'];
                $data = $linha['cl_data_lancamento'];
                $usuario_id = $linha['cl_usuario_id'];
                $qtd_ajuste = $linha['qtd'];
                $usuario = $linha['cl_usuario'];


            ?>
                <tr>
                    <th scope="row"><?= $documento; ?></th>
                    <td><?= formatDateB($data); ?></td>
                    <td><?= $qtd_ajuste; ?></td>
                    <td><?= $usuario; ?></td>
                    <td>
                        <button type="button" data-codigo="<?= $documento; ?>" data-codigo-nf="<?= $codigo_nf; ?>" class="btn btn-sm btn-info editar">Detalhe</button>
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
<script src="js/estoque/ajuste_estoque1/table/visualizar_historico_ajst.js">