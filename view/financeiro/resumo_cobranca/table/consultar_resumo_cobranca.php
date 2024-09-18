<?php
include "../../../../modal/financeiro/resumo_cobranca/gerenciar_resumo_cobranca.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>

                <th scope="col">Código</th>
                <th scope="col">Cliente</th>
                <th scope="col">Qtd. Duplicatas</th>
                <th scope="col">Vlr. Aberto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar_resumo_cobranca)) {
                $id = ($linha['codigo']);
                $razao_social = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia = utf8_encode($linha['cl_nome_fantasia']);
                $estado = utf8_encode($linha['cl_uf']);
                $cidade = utf8_encode($linha['cidade']);
                $endereco = utf8_encode($linha['cl_endereco']);
                $parceiro_id = utf8_encode($linha['cl_parceiro_id']);
                $codigo_nf = utf8_encode($linha['cl_codigo_nf']);

                $email = $linha['cl_email'];
                $cnpj_cpf = $linha['cl_cnpj_cpf'];
                $uf = $linha['cl_estado_id'];
                $email = $linha['cl_email'];
                $telefone = $linha['cl_telefone'];
                $qtdduplicatas = $linha['qtdduplicatas'];
                $valorduplicatas = $linha['valorduplicatas'];
            ?>
                <tr>
                    <th scope="row"><?= $id; ?></th>
                    <td class=""><?= $razao_social;  ?><br>
                        <hr class="mb-0"><?= $nome_fantasia . ", " . $endereco . " - " . $cidade . " - " . $estado ?>
                        <hr class="mb-0">Email: <?= $email . ", Telefone: " . $telefone; ?>
                    </td>
                    <td><?= ($qtdduplicatas); ?></td>
                    <td><?= real_format($valorduplicatas); ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="buttom" id='<?php echo $id; ?>' class="btn btn-sm btn-info editar">Visualizar</button>
                            <button type="buttom" id='<?php echo $id; ?>' data-parceiro-id='<?php echo $parceiro_id; ?>' data-codigo-nf='<?php echo $codigo_nf; ?>' class="btn btn-sm btn-warning contato">Registrar atendimento</button>
                        </div>
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
<script src="js/financeiro/resumo_cobranca/table/editar_resumo_cobranca.js"></script>