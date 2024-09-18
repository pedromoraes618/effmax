<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/configuracao/cfop/gerenciar_cfop.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Cfop Saida</th>
                <th scope="col">Descrição</th>
                <th scope="col">Cfop Entrada</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_cfop)) {
                $id_b = $linha['cl_id'];
                $codigo_cfop = utf8_encode($linha['cl_codigo_cfop']);
                $desc_cfop = utf8_encode($linha['cl_desc_cfop']);
                $cfop_entrada = $linha['cl_cfop_entrada'];

            ?>
                <tr>

                    <th><?php echo $codigo_cfop ?></th>
                    <td><?php echo $desc_cfop; ?></td>
                    <td><?php echo $cfop_entrada; ?></td>
                    <td class="td-btn"><button type="button" cfop_id=<?php echo $id_b; ?> class="btn btn-info   btn-sm editar_cfop ">Editar</button></td>
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
<script src="js/configuracao/cfop/table/editar_cfop.js">