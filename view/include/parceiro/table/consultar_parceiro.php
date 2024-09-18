<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/empresa/cliente/gerenciar_cliente.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>

            <tr>
                <th scope="col">Código</th>
                <th scope="col">Razão social</th>
                <th scope="col">Cnpj/Cpf</th>
                <th scope="col">Cidade</th>
                <th scope="col">Status</th>
                <th scope="col"></th>

            </tr>

        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_clientes)) {
                $cliente_id_b = $linha['cl_id'];
                $razao_social_b = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
                $cnpj_cpf_b = $linha['cl_cnpj_cpf'];
                $uf_b = $linha['cl_uf'];
                $cidade_b = utf8_encode($linha['cidade']);
                $situacao_b = $linha['cl_situacao_ativo'];
                $email_b = $linha['cl_email'];
                $bairro_b = $linha['cl_bairro'];
            ?>
                <tr class="rounded">

                    <th scope="row"><?php echo $cliente_id_b ?></th>
                    <td class="max_width_descricao"><?php echo $razao_social_b;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia_b . " / " .  $email_b; ?>
                    </td>
                    <td><?php echo formatCNPJCPF($cnpj_cpf_b);  ?></td>
                    <td><?php echo $cidade_b . ' - ' . $uf_b;  ?></td>
                    <td><span class='badge text-bg-<?php echo ($situacao_b == "SIM") ? 'success' : 'danger' ?>'><?php echo ($situacao_b == "SIM") ? 'Ativo' : 'Inativo' ?></td>

                    <td class="td-btn">
                        <?php if ($situacao_b == "SIM") { ?>
                            <button type="button" r_social=<?php echo $razao_social_b; ?> id_parceiro=<?php echo $cliente_id_b; ?> class="btn btn-info   btn-sm selecionar_parceiro ">Selecionar</button>
                        <?php } ?>
                    </td>

                    <td><input type="hidden" id="<?php echo $cliente_id_b; ?>" class="cliente_razao" value="<?php echo $razao_social_b; ?>"></td>

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
<script src="js/include/parceiro/pesquisa_parceiro.js">