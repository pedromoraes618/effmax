<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/empresa/credito/gerenciar_credito.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Razão Social</th>
                <th>Cnpj/Cpf</th>
                <th>Justificativa</th>
                <th>Valor Crédito</th>
                <th>Crédito Disponivel</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_parceiros)) {
                $id = utf8_encode($linha['cl_id']);
                $razao_social_b = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
                $cnpj_cpf_b = $linha['cl_cnpj_cpf'];
                //  $justificativa_credito = utf8_encode($linha['cl_justificativa_credito']);
                $valor_credito_disponivel = $linha['cl_valor_credito'];
            ?>
                <tr>
                    <td class="max_width_descricao"><?php echo $razao_social_b;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia_b; ?>
                    </td>
                    <td><?php echo formatCNPJCPF($cnpj_cpf_b); ?></td>
                    <td style="width: 450px;"><input type="text" name="justificativa_<?php echo $id; ?>" class="form-control" value="" placeholder="Ex. Devolução"></td>
                    <td style="width: 150px;"><input type="text" name="<?php echo $id; ?>" class="form-control" placeholder="Ex. 50"></td>
                    <td style="width: 150px;"><input type="text" class="form-control" disabled value="<?php echo $valor_credito_disponivel ?>"></td>
                    <td><button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-info consultar_historico_credito_parceiro">Historico</button></td>
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
<script src="js/empresa/credito/table/consultar_credito.js"></script>