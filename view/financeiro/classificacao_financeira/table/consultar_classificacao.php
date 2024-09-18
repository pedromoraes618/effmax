<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/classificacao_financeira/gerenciar_classificacao.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_classificacao)) {
                $id = $linha['cl_id'];
                $descricao = utf8_encode($linha['cl_descricao']);
            ?>
                <tr>
                    <th scope="row"><?php echo $id ?></th>
                    <td><?php echo $descricao; ?></td>
                    <td class="td-btn"><button type="button" classificacao_id=<?php echo $id; ?> class="btn btn-info   btn-sm editar_classificacao">Editar</button>
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
<script src="js/financeiro/classificacao_financeira/table/editar_classificacao.js"></script>