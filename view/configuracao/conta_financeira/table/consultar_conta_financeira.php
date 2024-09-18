<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/configuracao/conta_financeira/gerenciar_conta_financeira.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Conta</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_conta_financeira)) {
                $id_b = $linha['cl_id'];
                $descricao_b = utf8_encode($linha['cl_banco']);
                $conta_b = ($linha['cl_conta']);

            ?>
                <tr>

                    <th scope="row"><?php echo $id_b ?></th>
                    <td class="max_width_descricao"><?php echo $descricao_b; ?></td>
                    <td><?php echo $conta_b; ?></td>

                    <td class="td-btn"><button type="button" conta_financeira_id=<?php echo $id_b; ?> class="btn btn-info   btn-sm editar_conta_financeira ">Editar</button>
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
<script src="js/configuracao/conta_financeira/table/editar_conta_financeira.js">