<?php
include "../../../../modal/financeiro/resumo_cobranca/gerenciar_resumo_cobranca.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Documento</th>
                <th scope="col">Data vencimento</th>
                <th scope="col">Atraso</th>
                <th scope="col">Vlr. Aberto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar)) {
                $parceiro_id = ($linha['parceiroid']);
                $lancamento_id = ($linha['lancamentoid']);
                $codigo_nf = ($linha['cl_codigo_nf']);
                $razao_social = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia = utf8_encode($linha['cl_nome_fantasia']);
                $endereco = utf8_encode($linha['cl_endereco']);
                $estado = utf8_encode($linha['cl_uf']);
                $cidade = utf8_encode($linha['cidade']);
                $email = $linha['cl_email'];
                $cnpj_cpf = $linha['cl_cnpj_cpf'];
                $uf = $linha['cl_estado_id'];
                $email = $linha['cl_email'];
                $telefone = $linha['cl_telefone'];
                $data_vencimento = $linha['cl_data_vencimento'];
                $valor_liquido = $linha['cl_valor_liquido'];
                $documento = utf8_encode($linha['cl_documento']);
                $atraso = ($linha['atraso']);
                if ($atraso > 0) {
                    $atraso = $atraso . " Dia(s)";
                } else {
                    $atraso = null;
                }
            ?>
                <tr>
                    <td class=""><?= $razao_social;  ?><br>
                        <hr class="mb-0"><?= $nome_fantasia . ", " . $endereco . " - " . $cidade . " - " . $estado ?>
                        <hr class="mb-0">Email: <?= $email . ", Telefone: " . $telefone; ?>
                    </td>

                    <td><?= ($documento); ?></td>
                    <td><?= formatDateB($data_vencimento); ?></td>
                    <td><?= ($atraso); ?></td>
                    <td><?= real_format($valor_liquido); ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="buttom" id='<?php echo $lancamento_id; ?>' data-parceiro-id='<?php echo $parceiro_id; ?>' data-codigo-nf='<?php echo $codigo_nf; ?>' class="btn btn-sm btn-info editar">Visualizar</button>
                            <button type="buttom" id='<?php echo $lancamento_id; ?>' data-parceiro-id='<?php echo $parceiro_id; ?>' data-codigo-nf='<?php echo $codigo_nf; ?>' class="btn btn-sm btn-warning contato">Registrar atendimento</button>
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
<script src="js/financeiro/resumo_cobranca/table/editar_resumo_periodo.js"></script>